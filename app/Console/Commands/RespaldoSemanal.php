<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RespaldoSemanal extends Command
{
    protected $signature = 'db:respaldo-semanal';

    protected $description = 'Genera respaldo completo de las bases de datos del sistema PIHCSA';

    public function handle()
    {
        $rutaBackups = database_path('backups');

        if (! is_dir($rutaBackups)) {
            mkdir($rutaBackups, 0755, true);
        }

        $archivo = $rutaBackups . '/backup_pihcsa_full.sql';

        $usuario = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        /*
        |--------------------------------------------------------------------------
        | Bases a respaldar
        |--------------------------------------------------------------------------
        | mysql/principal = login y usuarios
        | sucursales = operación de activos por sucursal
        */
        $basesDeDatos = [
            env('DB_DATABASE'),              // gestion_activos_pihcsa_v2
            env('DB_DATABASE_MORELIA'),      // pihcsa_morelia
            env('DB_DATABASE_CDMX'),         // pihcsa_cdmx
            env('DB_DATABASE_LEON'),         // pihcsa_leon
        ];

        $basesDeDatos = array_filter($basesDeDatos);

        $bases = implode(' ', array_map('escapeshellarg', $basesDeDatos));

        if ($password === null || $password === '') {
            $comando = "mysqldump -u " . escapeshellarg($usuario) . " --databases $bases > " . escapeshellarg($archivo);
        } else {
            $comando = "mysqldump -u " . escapeshellarg($usuario) . " -p" . escapeshellarg($password) . " --databases $bases > " . escapeshellarg($archivo);
        }

        exec($comando, $output, $resultado);

        if ($resultado === 0) {
            $this->info('¡Respaldo completo generado correctamente!');
            $this->info('Archivo: ' . $archivo);
            return Command::SUCCESS;
        }

        $this->error('Error al generar el respaldo.');
        $this->error('Código de salida: ' . $resultado);

        return Command::FAILURE;
    }
}