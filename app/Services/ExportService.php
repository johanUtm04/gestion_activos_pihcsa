<?php

namespace App\Services;

use App\Models\Equipo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportService
{
    public function exportarInventarioCsv()
    {
        $fileName = 'Reporte_Inventario_' . now()->format('Y-m-d') . '.csv';

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

            /*
            |--------------------------------------------------------------------------
            | Compatibilidad con bases que todavía no tengan folio
            |--------------------------------------------------------------------------
            | Como manejas múltiples bases de datos, puede pasar que una sucursal tenga
            | la columna folio y otra todavía no. Por eso lo detectamos antes.
            */
            $tieneFolio = Schema::hasColumn('equipos', 'folio');

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

                'Usuario responsable',
                'Email responsable',
                'Departamento usuario',
                'Departamento equipo',
                'Ubicación',

                'Valor adquisición',
                'Fecha adquisición',
                'Fecha inicio uso',
                'Vida útil estimada',
                'Fecha último mantenimiento',

                'Fecha registro',
                'Fecha última actualización',
                'Fecha inactivación',
                'Motivo inactivación',

                'Procesadores activos',
                'Procesadores inactivos',
                'Detalle procesadores activos',
                'Detalle procesadores inactivos',

                'RAM activa',
                'RAM inactiva',
                'Detalle RAM activa',
                'Detalle RAM inactiva',

                'Discos activos',
                'Discos inactivos',
                'Detalle discos activos',
                'Detalle discos inactivos',

                'Monitores activos',
                'Monitores inactivos',
                'Detalle monitores activos',
                'Detalle monitores inactivos',

                'Periféricos activos',
                'Periféricos inactivos',
                'Detalle periféricos activos',
                'Detalle periféricos inactivos',
            ]);

            fputcsv($file, $encabezados);

            $contador = 1;

            Equipo::withTrashed()
                ->with([
                    'usuario',
                    'ubicacion',
                    'marca',
                    'tipoActivo',
                    'procesadores',
                    'rams',
                    'discosDuros',
                    'monitores',
                    'perifericos',
                ])
                ->orderBy('id')
                ->chunkById(200, function ($equipos) use (
                    $file,
                    &$contador,
                    $databaseName,
                    $sucursalActiva,
                    $tieneFolio
                ) {
                    foreach ($equipos as $equipo) {
                        $procesadoresActivos = $equipo->procesadores->where('is_active', 1);
                        $procesadoresInactivos = $equipo->procesadores->where('is_active', 0);

                        $ramsActivas = $equipo->rams->where('is_active', 1);
                        $ramsInactivas = $equipo->rams->where('is_active', 0);

                        $discosActivos = $equipo->discosDuros->where('is_active', 1);
                        $discosInactivos = $equipo->discosDuros->where('is_active', 0);

                        $monitoresActivos = $equipo->monitores->where('is_active', 1);
                        $monitoresInactivos = $equipo->monitores->where('is_active', 0);

                        $perifericosActivos = $equipo->perifericos->where('is_active', 1);
                        $perifericosInactivos = $equipo->perifericos->where('is_active', 0);

                        $fila = [
                            $contador++,
                            $this->limpiarTexto($this->nombreSucursal($sucursalActiva, $databaseName)),
                            $equipo->trashed() ? 'INACTIVO' : 'ACTIVO',
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
                            $this->limpiarTexto(optional($equipo->deleted_at)->format('Y-m-d H:i:s') ?? 'N/A'),
                            $this->limpiarTexto($equipo->motivo_inactivacion ?? 'N/A'),

                            $procesadoresActivos->count(),
                            $procesadoresInactivos->count(),
                            $this->formatRelacion($procesadoresActivos, function ($p) {
                                return trim(
                                    ($p->marca ?? 'N/A') . ' ' .
                                    ($p->descripcion_tipo ?? 'N/A') .
                                    ($p->clock_ghz ? " {$p->clock_ghz}GHz" : '')
                                );
                            }),
                            $this->formatRelacion($procesadoresInactivos, function ($p) {
                                return trim(
                                    ($p->marca ?? 'N/A') . ' ' .
                                    ($p->descripcion_tipo ?? 'N/A') .
                                    ($p->clock_ghz ? " {$p->clock_ghz}GHz" : '') .
                                    ' | Motivo: ' . ($p->motivo_inactivo ?? 'N/A')
                                );
                            }),

                            $this->sumarRam($ramsActivas),
                            $this->sumarRam($ramsInactivas),
                            $this->formatRelacion($ramsActivas, function ($r) {
                                return trim(
                                    ($r->capacidad_gb ?? 'N/A') . 'GB ' .
                                    ($r->tipo_chz ?? '') .
                                    ($r->clock_mhz ? " {$r->clock_mhz}MHz" : '') .
                                    ($r->serial ? " | Serial: {$r->serial}" : '')
                                );
                            }),
                            $this->formatRelacion($ramsInactivas, function ($r) {
                                return trim(
                                    ($r->capacidad_gb ?? 'N/A') . 'GB ' .
                                    ($r->tipo_chz ?? '') .
                                    ($r->clock_mhz ? " {$r->clock_mhz}MHz" : '') .
                                    ($r->serial ? " | Serial: {$r->serial}" : '') .
                                    ' | Motivo: ' . ($r->motivo_inactivo ?? 'N/A')
                                );
                            }),

                            $discosActivos->count(),
                            $discosInactivos->count(),
                            $this->formatRelacion($discosActivos, function ($d) {
                                return trim(
                                    ($d->capacidad ?? 'N/A') . ' ' .
                                    ($d->tipo_hdd_ssd ?? '') .
                                    ($d->interface ? " | Interface: {$d->interface}" : '') .
                                    ($d->serial ? " | Serial: {$d->serial}" : '')
                                );
                            }),
                            $this->formatRelacion($discosInactivos, function ($d) {
                                return trim(
                                    ($d->capacidad ?? 'N/A') . ' ' .
                                    ($d->tipo_hdd_ssd ?? '') .
                                    ($d->interface ? " | Interface: {$d->interface}" : '') .
                                    ($d->serial ? " | Serial: {$d->serial}" : '') .
                                    ' | Motivo: ' . ($d->motivo_inactivo ?? 'N/A')
                                );
                            }),

                            $monitoresActivos->count(),
                            $monitoresInactivos->count(),
                            $this->formatRelacion($monitoresActivos, function ($m) {
                                return trim(
                                    ($m->marca ?? 'N/A') .
                                    ($m->escala_pulgadas ? " {$m->escala_pulgadas}\"" : '') .
                                    ($m->interface ? " | Interface: {$m->interface}" : '') .
                                    ($m->serial ? " | Serial: {$m->serial}" : '')
                                );
                            }),
                            $this->formatRelacion($monitoresInactivos, function ($m) {
                                return trim(
                                    ($m->marca ?? 'N/A') .
                                    ($m->escala_pulgadas ? " {$m->escala_pulgadas}\"" : '') .
                                    ($m->interface ? " | Interface: {$m->interface}" : '') .
                                    ($m->serial ? " | Serial: {$m->serial}" : '') .
                                    ' | Motivo: ' . ($m->motivo_inactivo ?? 'N/A')
                                );
                            }),

                            $perifericosActivos->count(),
                            $perifericosInactivos->count(),
                            $this->formatRelacion($perifericosActivos, function ($p) {
                                return trim(
                                    ($p->tipo ?? 'N/A') .
                                    ($p->marca ? " {$p->marca}" : '') .
                                    ($p->interface ? " | Interface: {$p->interface}" : '') .
                                    ($p->serial ? " | Serial: {$p->serial}" : '')
                                );
                            }),
                            $this->formatRelacion($perifericosInactivos, function ($p) {
                                return trim(
                                    ($p->tipo ?? 'N/A') .
                                    ($p->marca ? " {$p->marca}" : '') .
                                    ($p->interface ? " | Interface: {$p->interface}" : '') .
                                    ($p->serial ? " | Serial: {$p->serial}" : '') .
                                    ' | Motivo: ' . ($p->motivo_inactivo ?? 'N/A')
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

        // Evita saltos raros dentro del CSV
        $texto = str_replace(["\r\n", "\r", "\n"], ' ', $texto);

        // Limpieza de espacios repetidos
        $texto = preg_replace('/\s+/', ' ', $texto);

        return trim($texto);
    }
}