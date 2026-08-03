<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Equipo;
use App\Models\Historial_log;
use Illuminate\Support\Facades\DB;

class GenerarHistorialInicial extends Command
{
    protected $signature = 'activos:reconstruir-historial';
    protected $description = 'Genera el log de Creacion con el formato exacto del Wizard';

    public function handle()
    {
        // 1. Buscamos equipos sin su log de 'Creacion'
        $equipos = Equipo::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('historiales_log')
                  ->whereColumn('historiales_log.activo_id', 'equipos.id')
                  ->where('historiales_log.tipo_registro', 'Creacion');
        })->get();

        if ($equipos->isEmpty()) {
            $this->info('Nada que reconstruir.');
            return;
        }

        $bar = $this->output->createProgressBar($equipos->count());
        $bar->start();

        foreach ($equipos as $equipo) {

        $this->eliminarExcedentesHardware($equipo);
            // Cargar relaciones para que el resumen no de error
            $equipo->load(['procesadores', 'rams', 'discosDuros', 'monitores', 'perifericos', 'usuario', 'marca', 'tipoActivo', 'ubicacion']);

            // REPLICAMOS LA LOGICA DEL WIZARD
            $hardwareString = $this->armarResumenHardware($equipo);

            Historial_log::create([
                'activo_id'         => $equipo->id,
                'usuario_accion_id' => 1, // ID de Marcos o Admin
                'tipo_registro'     => 'Creacion',
                'detalles_json'     => [
                    'mensaje' => 'Registro integral de nuevo activo y componentes',
                    'usuario_asignado' => $equipo->usuario->name ?? 'N/A',
                    'cambios' => [
                        'Tipo de Equipo'    => ['antes' => 'N/A', 'despues' => $equipo->tipoActivo->nombre ?? 'SIN-TIPO'],
                        'Usuario Asignado'  => ['antes' => 'N/A', 'despues' => $equipo->usuario->name ?? 'Sin Nombre'],
                        'Serial Equipo'     => ['antes' => 'N/A', 'despues' => $equipo->serial],
                        'Modelo'            => ['antes' => 'N/A', 'despues' => $equipo->modelo ?? 'N/A'],
                        'Marca'             => ['antes' => 'N/A', 'despues' => $equipo->marca->nombre ?? 'N/A'],
                        'Ubicación'         => ['antes' => 'N/A', 'despues' => $equipo->ubicacion->nombre ?? 'N/A'], 
                        'Sistema Operativo' => ['antes' => 'N/A', 'despues' => str_replace('|', ', ', $equipo->sistema_operativo)],
                        'Valor Inicial'     => ['antes' => 'N/A', 'despues' => '$' . number_format((float)$equipo->valor_inicial, 2)],
                        'Fecha Adquisición' => ['antes' => 'N/A', 'despues' => $equipo->fecha_adquisicion ?? 'N/A'],
                        'Vida Útil Estimada' => ['antes' => 'N/A', 'despues' => $equipo->vida_util_estimada . ' años'],
                        'Hardware Inicial'  => ['antes' => 'N/A', 'despues' => $hardwareString ?? 'N/A'],
                    ]
                ],
                // Respetamos la fecha original
                'created_at' => $equipo->created_at,
                'updated_at' => $equipo->created_at,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Historial clonado del Wizard con éxito.');
    }

    // COPIADO TAL CUAL DE TU CONTROLLER
private function armarResumenHardware($equipo): string
{
    $resumen = [
        // PROCESADORES (Por si tiene doble socket)
        'Procesador' => $equipo->procesadores->map(function($p) {
            return "{$p->marca} " . ($p->descripcion_tipo ? "({$p->descripcion_tipo})" : "");
        })->filter()->implode(' + ') ?: 'N/A',

        // RAMS (Aquí es donde capturamos las 2 o más RAMs)
        'RAM' => $equipo->rams->map(function($r) {
            return collect([
                $r->capacidad_gb ? "{$r->capacidad_gb}GB" : null,
                $r->tipo_chz,
                $r->clock_mhz ? "{$r->clock_mhz}MHz" : null,
            ])->filter()->implode(' ');
        })->implode(' • ') ?: 'N/A',

        // DISCOS (Muestra todos: SSD + HDD)
        'Disco' => $equipo->discosDuros->map(function($d) {
            return "{$d->capacidad} {$d->tipo_hdd_ssd} ({$d->interface})";
        })->implode(' + ') ?: 'N/A',

        // MONITORES
        'Monitor' => $equipo->monitores->map(function($m) {
            return "{$m->marca} " . ($m->escala_pulgadas ? "{$m->escala_pulgadas}\"" : "");
        })->implode(' • ') ?: 'N/A',

        // PERIFÉRICOS
        'Periférico' => $equipo->perifericos->map(function($p) {
            return "{$p->tipo} " . ($p->marca ? "({$p->marca})" : "");
        })->implode(' • ') ?: 'N/A',
    ];

    // Mantenemos tu formato de asteriscos y pipes para que se vea igual a la imagen
    return collect($resumen)->map(fn($v, $k) => "**$k**: $v")->implode(' | ');
}

private function eliminarExcedentesHardware($equipo)
{
    // Relaciones basadas en tus tablas (rams, monitores, etc)
    $tablas = ['procesadores', 'rams', 'discosDuros', 'monitores', 'perifericos'];

    foreach ($tablas as $relacion) {
        
        // --- PASO A: ELIMINAR INACTIVOS DE UNA VEZ ---
        // Si is_active es 0, se va de la DB y no entra en la conversación
        $equipo->$relacion()->where('is_active', 0)->delete(); //

        // --- PASO B: QUEDARSE SOLO CON EL PRIMERO ACTIVO ---
        $componentesActivos = $equipo->$relacion()
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get();

        if ($componentesActivos->count() > 1) {
            // Conservamos el ID más antiguo de los activos
            $idParaConservar = $componentesActivos->first()->id;

            // Borramos el resto de activos para cumplir el "molde" del Inge
            $equipo->$relacion()
                ->where('id', '!=', $idParaConservar)
                ->delete();
        }
    }
}




}