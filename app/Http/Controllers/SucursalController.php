<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SucursalController extends Controller
{
    public function index()
    {
        $this->validarAdmin();

        $sucursales = Sucursal::orderBy('nombre')->get();

        return view('sucursales.index', compact('sucursales'));
    }

    public function cambiar(Request $request)
    {
        $this->validarAdmin();

        $request->validate([
            'sucursal' => ['required', 'string'],
        ]);

        $sucursal = Sucursal::activas()
            ->where('clave', $request->sucursal)
            ->first();

        if (! $sucursal) {
            throw ValidationException::withMessages([
                'sucursal' => 'La sucursal seleccionada no existe o está inactiva.',
            ]);
        }

        session()->put('sucursal_activa', $sucursal->clave);

        // Importante para vehículos: evita arrastrar empresa_id entre sucursales
        session()->forget('empresa_id');

        $this->aplicarConexionSucursal($sucursal->clave, $sucursal->database_name);

        return back()->with('status', 'Sucursal activa cambiada correctamente.');
    }

    public function generar(Request $request)
    {
        $this->validarAdmin();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'clave' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('mysql.sucursales', 'clave'),
            ],
            'database_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('mysql.sucursales', 'database_name'),
            ],
            'descripcion' => ['nullable', 'string'],
        ]);

        $clave = strtolower(trim($data['clave']));
        $databaseName = strtolower(trim($data['database_name']));

        if (! str_starts_with($databaseName, 'pihcsa_')) {
            return back()
                ->withInput()
                ->with('danger', 'El nombre de la base debe iniciar con pihcsa_. Ejemplo: pihcsa_queretaro');
        }

        $existeDb = DB::connection('mysql')->select(
            'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?',
            [$databaseName]
        );

        if (! empty($existeDb)) {
            return back()
                ->withInput()
                ->with('danger', "La base de datos {$databaseName} ya existe.");
        }

        try {
            DB::connection('mysql')->statement(
                "CREATE DATABASE `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );

            $this->registrarConexionDinamica($clave, $databaseName);

            $dbPlantilla = config('database.connections.mysql.database');

            $this->clonarEstructuraDesdePlantilla($dbPlantilla, $databaseName);

            Sucursal::create([
                'clave' => $clave,
                'nombre' => $data['nombre'],
                'database_name' => $databaseName,
                'estatus' => 'activo',
                'descripcion' => $data['descripcion'] ?? null,
            ]);

            return redirect()
                ->route('sucursales.index')
                ->with('success', "Sucursal {$data['nombre']} generada correctamente.");

        } catch (\Throwable $e) {
            try {
                DB::connection('mysql')->statement("DROP DATABASE IF EXISTS `$databaseName`");
            } catch (\Throwable $cleanupError) {
                // No hacemos nada extra
            }

            return back()
                ->withInput()
                ->with('danger', 'Error al generar la sucursal: ' . $e->getMessage());
        }
    }

    public function destroy(Sucursal $sucursal)
    {
        $this->validarAdmin();

        $basesProtegidas = [
            config('database.connections.mysql.database'),
            'gestion_activos_pihcsa_v2',
        ];

        $clavesProtegidas = [
            'principal',
        ];

        if (
            in_array($sucursal->database_name, $basesProtegidas, true) ||
            in_array($sucursal->clave, $clavesProtegidas, true)
        ) {
            return back()->with('danger', 'No se puede eliminar la base principal del sistema.');
        }

        if (! str_starts_with($sucursal->database_name, 'pihcsa_')) {
            return back()->with('danger', 'Por seguridad, esta base de datos no puede eliminarse desde el sistema.');
        }

        try {
            DB::connection('mysql')->statement(
                "DROP DATABASE IF EXISTS `{$sucursal->database_name}`"
            );

            $sucursal->delete();

            if (session('sucursal_activa') === $sucursal->clave) {
                session()->put('sucursal_activa', 'principal');
                session()->forget('empresa_id');
            }

            return back()->with('success', "Sucursal {$sucursal->nombre} eliminada correctamente.");

        } catch (\Throwable $e) {
            return back()->with('danger', 'Error al eliminar la sucursal: ' . $e->getMessage());
        }
    }

    private function validarAdmin(): void
    {
        $rol = strtoupper(trim(auth()->user()?->rol ?? ''));

        abort_unless($rol === 'ADMIN', 403);
    }

    private function aplicarConexionSucursal(string $clave, string $databaseName): void
    {
        $this->registrarConexionDinamica($clave, $databaseName);

        config(['database.default' => $clave]);

        DB::setDefaultConnection($clave);
        DB::purge($clave);
        DB::reconnect($clave);
    }

    private function registrarConexionDinamica(string $clave, string $databaseName): void
    {
        $mysql = config('database.connections.mysql');

        config([
            "database.connections.$clave" => [
                'driver' => $mysql['driver'] ?? 'mysql',
                'host' => $mysql['host'] ?? '127.0.0.1',
                'port' => $mysql['port'] ?? '3306',
                'database' => $databaseName,
                'username' => $mysql['username'],
                'password' => $mysql['password'],
                'unix_socket' => $mysql['unix_socket'] ?? '',
                'charset' => $mysql['charset'] ?? 'utf8mb4',
                'collation' => $mysql['collation'] ?? 'utf8mb4_unicode_ci',
                'prefix' => $mysql['prefix'] ?? '',
                'prefix_indexes' => $mysql['prefix_indexes'] ?? true,
                'strict' => $mysql['strict'] ?? true,
                'engine' => $mysql['engine'] ?? null,
                'options' => $mysql['options'] ?? [],
            ],
        ]);
    }

    private function clonarEstructuraDesdePlantilla(string $dbPlantilla, string $dbNueva): void
    {
        $tablas = DB::connection('mysql')->select("SHOW TABLES FROM `$dbPlantilla`");

        foreach ($tablas as $tablaObj) {
            $tablaArray = (array) $tablaObj;
            $tabla = array_values($tablaArray)[0];

            if (in_array($tabla, [
                'migrations',
                'personal_access_tokens',
                'sucursales',
            ], true)) {
                continue;
            }

            DB::connection('mysql')->statement(
                "CREATE TABLE `$dbNueva`.`$tabla` LIKE `$dbPlantilla`.`$tabla`"
            );
        }

        $tablasCatalogo = [
            'users',
            'ubicaciones',
            'marcas',
            'tipo_activos',
            'empresas',
            'cat_tipo_vehiculos',
            'tasas_lisr',
            'inpc_indices',
            'marca_equipo_tipo_equipo',
        ];

        foreach ($tablasCatalogo as $tabla) {
            $existe = DB::connection('mysql')->select(
                "SELECT TABLE_NAME
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_SCHEMA = ?
                 AND TABLE_NAME = ?",
                [$dbNueva, $tabla]
            );

            if (! empty($existe)) {
                DB::connection('mysql')->statement(
                    "INSERT INTO `$dbNueva`.`$tabla` SELECT * FROM `$dbPlantilla`.`$tabla`"
                );
            }
        }
    }

    //Funcion para seleccionar sucursale despues del login
    public function seleccionar(){
        $sucursales = Sucursal::on ('mysql')
        ->where ('estatus', 'activo')
        ->orderBy('nombre')
        -get();
        return view('sucursales.seleccionar', compact(sucursales));
    }

    public function guardarSeleccion (Request $request)
    {
        $request -> validate ([
            'sucursal' => ['required', 'sring'],
        ]);

        $sucursal = Sucursal::on ('mysql')
        ->where ('estatus', 'activo')
        ->where ('clave', $request->sucursal)
        ->first();

        if(! $sucursal){
            throw ValidationException::withMessages([
                'sucursal' => 'La sucursal seleccionada no existe o se encuentra incativa'
            ]);
        }

        session()->put ('sucursal_activa', $sucursal->clave);
        session()->forget('empresa_id');

        $this->aplicarConexionSucursal($sucursal->clave, $sucursal->database_name);

        return redirect ()
        ->route('equipos.index')
        ->with('Success', 'Base de datos seleccionada correctamente');
    }

}