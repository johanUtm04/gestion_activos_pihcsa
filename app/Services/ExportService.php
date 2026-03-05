<?php

namespace App\Services;

use App\Models\Equipo;
use Illuminate\Support\Facades\Log;

class ExportService
{
    public function exportarInventarioCsv()
    {
        $fileName = 'Reporte_Inventario_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM para Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Encabezados
            fputcsv($file, [
                'ID', 'Usuario', 'Ubicación','Departamento', 'Tipo', 'Marca', 'Modelo', 
                'Serial', 'Factura', 'OS', 'Procesador', 'RAM', 'Disco', 'Monitores'
            ]);

            Equipo::with(['usuario', 'ubicacion', 'marca', 'tipoActivo', 'monitores', 'discosDuros', 'rams', 'procesadores'])
                ->cursor() 
                ->each(function ($equipo) use ($file) {
                    fputcsv($file, [
                        $equipo->id,
                        $equipo->usuario?->name ?? 'Disponible',
                        $equipo->ubicacion?->nombre ?? 'N/A',
                        $equipo->departamento_perteneciente ?? 'N/A',
                        $equipo->tipoActivo?->nombre ?? 'N/A',
                        $equipo->marca?->nombre ?? 'N/A',
                        $equipo->modelo,
                        $equipo->serial,
                        $equipo->numero_factura,
                        $equipo->sistema_operativo,
                        $this->formatRelacion($equipo->procesadores, fn($p) => "{$p->marca} {$p->descripcion_tipo}"),
                        $this->formatRelacion($equipo->rams, fn($r) => "{$r->capacidad_gb}GB {$r->tipo_chz}"),
                        $this->formatRelacion($equipo->discosDuros, fn($d) => "{$d->capacidad}GB {$d->tipo_hdd_ssd}"),
                        $this->formatRelacion($equipo->monitores, fn($m) => "{$m->marca} {$m->escala_pulgadas}\""),
                    ]);
                });

            fclose($file);
        };

        return [$callback, $headers];
    }

    private function formatRelacion($coleccion, $callback)
    {
        return $coleccion->isEmpty() ? 'N/A' : $coleccion->map($callback)->implode(' | ');
    }
}