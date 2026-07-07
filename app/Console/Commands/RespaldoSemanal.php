<?php

namespace App\Console\Commands;

use App\Models\Sucursal;
use Illuminate\Console\Command;

class RespaldoSemanal extends Command
{
    protected $signature = 'db:respaldo-semanal';

    protected $description = 'Genera respaldo completo de la base principal y las bases activas por sucursal';

    public function handle()
    {
        $rutaBackups = database_path('backups');

        if (! is_dir($rutaBackups)) {
            mkdir($rutaBackups, 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | Archivo de respaldo
        |--------------------------------------------------------------------------
        | Este archivo se reemplaza cada vez que se ejecuta el comando.
        */
        $archivo = $rutaBackups . DIRECTORY_SEPARATOR . 'backup_pihcsa_full.sql';

        $usuario = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        /*
        |--------------------------------------------------------------------------
        | Base principal
        |--------------------------------------------------------------------------
        | Aquí viven login, users y tabla sucursales.
        */
        $basePrincipal = env('DB_DATABASE');

        /*
        |--------------------------------------------------------------------------
        | Bases de sucursales activas
        |--------------------------------------------------------------------------
        | Se leen desde gestion_activos_pihcsa_v2.sucursales
        */
        $basesSucursales = Sucursal::activas()
            ->pluck('database_name')
            ->toArray();

        $basesDeDatos = array_values(array_unique(array_filter(array_merge(
            [$basePrincipal],
            $basesSucursales
        ))));

        if (empty($basesDeDatos)) {
            $this->error('No hay bases de datos configuradas para respaldar.');
            return Command::FAILURE;
        }

        $this->info('Bases a respaldar:');
        foreach ($basesDeDatos as $db) {
            $this->line('- ' . $db);
        }

        $bases = implode(' ', array_map('escapeshellarg', $basesDeDatos));

        /*
        |--------------------------------------------------------------------------
        | Comando mysqldump
        |--------------------------------------------------------------------------
        | --databases incluye CREATE DATABASE y USE, útil para restaurar en otra PC.
        */
        if ($password === null || $password === '') {
            $comando = 'mysqldump -u ' . escapeshellarg($usuario)
                . ' --databases ' . $bases
                . ' > ' . escapeshellarg($archivo);
        } else {
            $comando = 'mysqldump -u ' . escapeshellarg($usuario)
                . ' -p' . escapeshellarg($password)
                . ' --databases ' . $bases
                . ' > ' . escapeshellarg($archivo);
        }

        exec($comando, $output, $resultado);

        if ($resultado === 0) {
            $this->info('');
            $this->info('¡Respaldo completo generado correctamente!');
            $this->info('Archivo: ' . $archivo);

            return Command::SUCCESS;
        }

        $this->error('');
        $this->error('Error al generar el respaldo.');
        $this->error('Código de salida: ' . $resultado);

        return Command::FAILURE;
    }
}