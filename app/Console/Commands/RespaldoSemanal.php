<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RespaldoSemanal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:respaldo-semanal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{

    $ruta = '/srv/www/htdocs/gestion_activos_pihcsa/database/backups/backup_actualizado.sql';

    $usuario = env('DB_USERNAME');
    $password = env('DB_PASSWORD');
    $baseDeDatos = env('DB_DATABASE');

    $comando = "mysqldump -u $usuario -p'$password' $baseDeDatos > $ruta";

    exec($comando, $output, $resultado);

    if ($resultado === 0) {
        $this->info('¡El respaldo se hizo y se reemplazó el anterior!');
    }
}
}
