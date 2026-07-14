<?php

namespace App\Services;

use App\Models\Equipo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportService
{
    public function exportarInventarioCsv()
    {
        $fileName = 'Reporte_Inventario_Activos_Actuales_' . now()->format('Y-m-d') . '.csv';

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

            $tieneFolio = Schema::hasColumn('equipos', 'folio');
            $tieneIsActive = Schema::hasColumn('equipos', 'is_active');

            /*
            |--------------------------------------------------------------------------
            | Encabezados
            |--------------------------------------------------------------------------
            | Este reporte es de inventario actual.
            | No incluye movimientos históricos ni componentes inactivos.
            */
            $encabezados = [
                'No.',
                'Base / Sucursal',
                'Estado del activo',
                'ID BD',
            ];

            if ($tieneFolio) {
                $encabezados[] = 'Folio';
            }

            $encabezados = array_merge($encabezados, [
                'Tipo de activo',
                'Marca',
                'Modelo',
                'Serial',
                'Factura',
                'Sistema operativo',

                'Usuario actual',
                'Email usuario actual',
                'Departamento usuario actual',
                'Departamento equipo',
                'Ubicación actual',

                'Valor adquisición',
                'Fecha adquisición',
                'Fecha inicio uso',
                'Vida útil estimada',
                'Fecha último mantenimiento',

                'Fecha registro',
                'Fecha última actualización',

                'Procesadores actuales',
                'Detalle procesadores actuales',

                'RAM actual',
                'Detalle RAM actual',

                'Discos actuales',
                'Detalle discos actuales',

                'Monitores actuales',
                'Detalle monitores actuales',

                'Periféricos actuales',
                'Detalle periféricos actuales',
            ]);

            fputcsv($file, $encabezados);

            $contador = 1;

            /*
            |--------------------------------------------------------------------------
            | Consulta principal
            |--------------------------------------------------------------------------
            | Regla del reporte:
            | 1 fila = 1 activo actual.
            |
            | No usamos withTrashed().
            | No generamos filas por historial.
            | No generamos filas por movimientos.
            | No mostramos componentes inactivos.
            */
            $query = Equipo::with([
                'usuario',
                'ubicacion',
                'marca',
                'tipoActivo',
                'procesadores',
                'rams',
                'discosDuros',
                'monitores',
                'perifericos',
            ]);

            if ($tieneIsActive) {
                $query->where('is_active', 1);
            }

            $query->orderBy('id')
                ->chunkById(200, function ($equipos) use (
                    $file,
                    &$contador,
                    $databaseName,
                    $sucursalActiva,
                    $tieneFolio
                ) {
                    foreach ($equipos as $equipo) {
                        /*
                        |--------------------------------------------------------------------------
                        | Solo componentes activos
                        |--------------------------------------------------------------------------
                        | Los componentes inactivos o movimientos anteriores pertenecen
                        | a Historial / Auditoría, no al reporte actual.
                        */
                        $procesadoresActivos = $this->filtrarActivos($equipo->procesadores);
                        $ramsActivas = $this->filtrarActivos($equipo->rams);
                        $discosActivos = $this->filtrarActivos($equipo->discosDuros);
                        $monitoresActivos = $this->filtrarActivos($equipo->monitores);
                        $perifericosActivos = $this->filtrarActivos($equipo->perifericos);

                        $fila = [
                            $contador++,
                            $this->limpiarTexto($this->nombreSucursal($sucursalActiva, $databaseName)),
                            'ACTIVO',
                            $equipo->id,
                        ];

                        if ($tieneFolio) {
                            $fila[] = $equipo->folio ?? 'N/A';
                        }

                        $fila = array_merge($fila, [
                            $this->limpiarTexto($equipo->tipoActivo?->nombre ?? $equipo->tipo_equipo ?? 'N/A'),
                            $this->limpiarTexto($equipo->marca?->nombre ?? $equipo->marca_equipo ?? 'N/A'),
                            $this->limpiarTexto($equipo->modelo ?? 'N/A'),
                            $this->limpiarTexto($equipo->serial ?? 'N/A'),
                            $this->limpiarTexto($equipo->numero_factura ?? 'N/A'),
                            $this->limpiarTexto($this->formatearSistemaOperativo($equipo->sistema_operativo)),

                            $this->limpiarTexto($equipo->usuario?->name ?? 'Disponible / Sin asignar'),
                            $this->limpiarTexto($equipo->usuario?->email ?? 'N/A'),
                            $this->limpiarTexto($equipo->usuario?->departamento ?? 'N/A'),
                            $this->limpiarTexto($equipo->departamento_perteneciente ?? 'N/A'),
                            $this->limpiarTexto($equipo->ubicacion?->nombre ?? 'N/A'),

                            $this->formatearMoneda($equipo->valor_inicial),
                            $this->limpiarTexto($equipo->fecha_adquisicion ?? 'N/A'),
                            $this->limpiarTexto($equipo->fecha_inicio_uso ?? 'N/A'),
                            $this->limpiarTexto($equipo->vida_util_estimada ?? 'N/A'),
                            $this->limpiarTexto($equipo->fecha_ultimo_mantenimiento ?? 'N/A'),

                            $this->limpiarTexto(optional($equipo->created_at)->format('Y-m-d H:i:s') ?? 'N/A'),
                            $this->limpiarTexto(optional($equipo->updated_at)->format('Y-m-d H:i:s') ?? 'N/A'),

                            $procesadoresActivos->count(),
                            $this->formatRelacion($procesadoresActivos, function ($p) {
                                return trim(
                                    ($p->marca ?? 'N/A') . ' ' .
                                    ($p->descripcion_tipo ?? 'N/A') .
                                    ($p->clock_ghz ? " {$p->clock_ghz}GHz" : '')
                                );
                            }),

                            $this->sumarRam($ramsActivas),
                            $this->formatRelacion($ramsActivas, function ($r) {
                                return trim(
                                    ($r->capacidad_gb ?? 'N/A') . 'GB ' .
                                    ($r->tipo_chz ?? '') .
                                    ($r->clock_mhz ? " {$r->clock_mhz}MHz" : '') .
                                    ($r->serial ? " | Serial: {$r->serial}" : '')
                                );
                            }),

                            $discosActivos->count(),
                            $this->formatRelacion($discosActivos, function ($d) {
                                return trim(
                                    ($d->capacidad ?? 'N/A') . ' ' .
                                    ($d->tipo_hdd_ssd ?? '') .
                                    ($d->interface ? " | Interface: {$d->interface}" : '') .
                                    ($d->serial ? " | Serial: {$d->serial}" : '')
                                );
                            }),

                            $monitoresActivos->count(),
                            $this->formatRelacion($monitoresActivos, function ($m) {
                                return trim(
                                    ($m->marca ?? 'N/A') .
                                    ($m->escala_pulgadas ? " {$m->escala_pulgadas}\"" : '') .
                                    ($m->interface ? " | Interface: {$m->interface}" : '') .
                                    ($m->serial ? " | Serial: {$m->serial}" : '')
                                );
                            }),

                            $perifericosActivos->count(),
                            $this->formatRelacion($perifericosActivos, function ($p) {
                                return trim(
                                    ($p->tipo ?? 'N/A') .
                                    ($p->marca ? " {$p->marca}" : '') .
                                    ($p->interface ? " | Interface: {$p->interface}" : '') .
                                    ($p->serial ? " | Serial: {$p->serial}" : '')
                                );
                            }),
                        ]);

                        fputcsv($file, $fila);
                    }
                });

            fclose($file);
        };

        return [$callback, $headers];
    }

    /*
    |--------------------------------------------------------------------------
    | Filtra relaciones activas
    |--------------------------------------------------------------------------
    | Si la relación tiene columna is_active, solo deja activos.
    | Si no trae is_active, se conserva porque se considera dato actual.
    */
    private function filtrarActivos($coleccion)
    {
        return $coleccion->filter(function ($item) {
            if (! isset($item->is_active)) {
                return true;
            }

            return (int) $item->is_active === 1;
        })->values();
    }

    private function formatRelacion($coleccion, callable $callback): string
    {
        if ($coleccion->isEmpty()) {
            return 'N/A';
        }

        return $this->limpiarTexto(
            $coleccion
                ->map($callback)
                ->filter()
                ->implode(' | ')
        );
    }

    private function sumarRam($coleccion): string
    {
        if ($coleccion->isEmpty()) {
            return '0GB';
        }

        $total = $coleccion->sum(function ($ram) {
            return (float) preg_replace('/[^0-9.]/', '', (string) $ram->capacidad_gb);
        });

        return $total . 'GB';
    }

    private function formatearSistemaOperativo(?string $so): string
    {
        if (empty($so)) {
            return 'N/A';
        }

        return str_replace('|', ' ', $so);
    }

    private function formatearMoneda($valor): string
    {
        if ($valor === null || $valor === '') {
            return '$0.00';
        }

        $numero = (float) preg_replace('/[^0-9.]/', '', (string) $valor);

        return '$' . number_format($numero, 2);
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