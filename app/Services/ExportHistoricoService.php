<?php

namespace App\Services;

use App\Models\Historial_log;
use Illuminate\Support\Facades\DB;

class ExportHistoricoService
{
    public function exportarHistoricoCsv()
    {
        $fileName = 'Reporte_Historico_Movimientos_Activos_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM para Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $databaseName = DB::connection()->getDatabaseName();
            $sucursalActiva = session('sucursal_activa', 'N/A');

            $encabezados = [
                'No.',
                'Base / Sucursal',
                'ID Movimiento',
                'ID Activo',
                'Folio Activo',
                'Tipo de activo',
                'Marca',
                'Modelo',
                'Serial',
                'Usuario actual del activo',
                'Ubicación actual del activo',
                'Tipo de movimiento',
                'Usuario que realizó la acción',
                'Email usuario acción',
                'Fecha movimiento',
                'Detalle del movimiento',
            ];

            fputcsv($file, $encabezados);

            $contador = 1;

            Historial_log::with([
                    'equipo.tipoActivo',
                    'equipo.marca',
                    'equipo.usuario',
                    'equipo.ubicacion',
                    'usuario',
                ])
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->chunkById(200, function ($movimientos) use (
                    $file,
                    &$contador,
                    $databaseName,
                    $sucursalActiva
                ) {
                    foreach ($movimientos as $movimiento) {
                        $equipo = $movimiento->equipo;
                        $usuarioAccion = $movimiento->usuario;

                        $fila = [
                            $contador++,
                            $this->limpiarTexto($this->nombreSucursal($sucursalActiva, $databaseName)),
                            $movimiento->id,
                            $movimiento->activo_id,
                            $equipo?->folio ?? 'N/A',
                            $this->limpiarTexto($equipo?->tipoActivo?->nombre ?? $equipo?->tipo_equipo ?? 'N/A'),
                            $this->limpiarTexto($equipo?->marca?->nombre ?? $equipo?->marca_equipo ?? 'N/A'),
                            $this->limpiarTexto($equipo?->modelo ?? 'N/A'),
                            $this->limpiarTexto($equipo?->serial ?? 'N/A'),
                            $this->limpiarTexto($equipo?->usuario?->name ?? 'Disponible / Sin asignar'),
                            $this->limpiarTexto($equipo?->ubicacion?->nombre ?? 'N/A'),
                            $this->limpiarTexto($movimiento->tipo_registro ?? 'N/A'),
                            $this->limpiarTexto($usuarioAccion?->name ?? 'N/A'),
                            $this->limpiarTexto($usuarioAccion?->email ?? 'N/A'),
                            $this->limpiarTexto(optional($movimiento->created_at)->format('Y-m-d H:i:s') ?? 'N/A'),
                            $this->formatearDetalles($movimiento->detalles_json),
                        ];

                        fputcsv($file, $fila);
                    }
                });

            fclose($file);
        };

        return [$callback, $headers];
    }

    private function formatearDetalles($detalles): string
    {
        if (empty($detalles)) {
            return 'N/A';
        }

        if (is_string($detalles)) {
            $decoded = json_decode($detalles, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $detalles = $decoded;
            } else {
                return $this->limpiarTexto($detalles);
            }
        }

        if (! is_array($detalles)) {
            return $this->limpiarTexto((string) $detalles);
        }

        return $this->limpiarTexto($this->arrayAtexto($detalles));
    }

    private function arrayAtexto(array $array, string $prefijo = ''): string
    {
        $partes = [];

        foreach ($array as $key => $value) {
            $label = $prefijo ? "{$prefijo}.{$key}" : $key;

            if (is_array($value)) {
                $partes[] = $this->arrayAtexto($value, $label);
                continue;
            }

            if (is_bool($value)) {
                $value = $value ? 'Sí' : 'No';
            }

            if ($value === null || $value === '') {
                $value = 'N/A';
            }

            $partes[] = "{$label}: {$value}";
        }

        return implode(' | ', array_filter($partes));
    }

    private function nombreSucursal(?string $clave, ?string $databaseName): string
    {
        $clave = $clave ?: 'N/A';
        $databaseName = $databaseName ?: 'N/A';

        return strtoupper($clave) . ' / ' . $databaseName;
    }

    private function limpiarTexto($texto): string
    {
        $texto = (string) ($texto ?? 'N/A');

        $texto = str_replace(["\r\n", "\r", "\n"], ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);

        return trim($texto);
    }
}