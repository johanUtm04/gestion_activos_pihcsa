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

            // UTF-8 BOM para que Excel respete acentos y ñ
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
                'Cuenta Contable',
                'Pedimento',
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
                ->lazy(200)
                ->each(function ($movimiento) use (
                    $file,
                    &$contador,
                    $databaseName,
                    $sucursalActiva
                ) {
                    $equipo = $movimiento->equipo;
                    $usuarioAccion = $movimiento->usuario;

                    $fila = [
                        $contador++,
                        $this->limpiarTexto($this->nombreSucursal($sucursalActiva, $databaseName)),
                        $movimiento->id,
                        $movimiento->activo_id,
                        $this->limpiarTexto($equipo?->folio ?? 'N/A'),
                        $this->limpiarTexto($equipo?->tipoActivo?->nombre ?? $equipo?->tipo_equipo ?? 'N/A'),
                        $this->limpiarTexto($equipo?->marca?->nombre ?? $equipo?->marca_equipo ?? 'N/A'),
                        $this->limpiarTexto($equipo?->modelo ?? 'N/A'),
                        $this->limpiarTexto($equipo?->serial ?? 'N/A'),
                        $this->limpiarTexto($equipo?->cuenta_contable ?? 'N/A'),
                        $this->limpiarTexto($equipo?->pedimento ?? 'N/A'),
                        $this->limpiarTexto($equipo?->usuario?->name ?? 'Disponible / Sin asignar'),
                        $this->limpiarTexto($equipo?->ubicacion?->nombre ?? 'N/A'),
                        $this->limpiarTexto($movimiento->tipo_registro ?? 'N/A'),
                        $this->limpiarTexto($usuarioAccion?->name ?? 'N/A'),
                        $this->limpiarTexto($usuarioAccion?->email ?? 'N/A'),
                        $this->limpiarTexto(optional($movimiento->created_at)->format('Y-m-d H:i:s') ?? 'N/A'),
                        $this->formatearDetalles($movimiento->detalles_json),
                    ];

                    fputcsv($file, $fila);
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
                $textoInterno = $this->arrayAtexto($value, $label);

                if ($textoInterno !== '') {
                    $partes[] = $textoInterno;
                }

                continue;
            }

            if (is_bool($value)) {
                $value = $value ? 'Sí' : 'No';
            }

            if ($value === null || $value === '') {
                $value = 'N/A';
            }

            $label = $this->normalizarEtiqueta($label);
            $value = $this->limpiarTexto($value);

            $partes[] = "{$label}: {$value}";
        }

        return implode(' | ', array_filter($partes));
    }

    private function normalizarEtiqueta(string $label): string
    {
        $label = str_replace(['_', '-'], ' ', $label);
        $label = str_replace('.', ' > ', $label);

        return trim($label);
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

        // Convierte saltos HTML comunes a separadores legibles antes de quitar etiquetas.
        $texto = preg_replace('/<\s*br\s*\/?>/i', ' | ', $texto);
        $texto = preg_replace('/<\/\s*p\s*>/i', ' | ', $texto);
        $texto = preg_replace('/<\/\s*div\s*>/i', ' | ', $texto);
        $texto = preg_replace('/<\/\s*li\s*>/i', ' | ', $texto);

        // Quita cualquier etiqueta HTML restante.
        $texto = strip_tags($texto);

        // Decodifica entidades HTML.
        $texto = html_entity_decode($texto, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Limpieza de espacios y saltos.
        $texto = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);

        // Limpieza de separadores repetidos.
        $texto = preg_replace('/(\s*\|\s*)+/', ' | ', $texto);
        $texto = trim($texto, " \t\n\r\0\x0B|");

        if ($texto === '') {
            $texto = 'N/A';
        }

        // Hardening contra fórmulas en Excel.
        if (preg_match('/^[=+\-@]/', $texto)) {
            $texto = "'" . $texto;
        }

        return trim($texto);
    }
}