<?php

namespace App\Services;

use App\Models\Equipo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepreciacionReportService
{
    public function exportarDetalleAnualCsv(array $filtros = [])
    {
        $fileName = 'Reporte_Depreciacion_Por_Anio_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($filtros) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $databaseName = DB::connection()->getDatabaseName();
            $sucursalActiva = session('sucursal_activa', 'N/A');

            fputcsv($file, [
                'No.',
                'Base / Sucursal',
                'Año',
                'ID Activo',
                'Tipo de activo',
                'Marca',
                'Modelo',
                'Serial',
                'Usuario actual',
                'Ubicación actual',
                'Valor inicial',
                'Fecha adquisición',
                'Vida útil estimada',
                'Depreciación del año',
                'Depreciación acumulada',
                'Valor en libros',
            ]);

            $contador = 1;

            Equipo::with(['usuario', 'ubicacion', 'marca', 'tipoActivo'])
                ->filtrar($filtros)
                ->orderBy('id')
                ->chunkById(200, function ($equipos) use (
                    $file,
                    &$contador,
                    $databaseName,
                    $sucursalActiva
                ) {
                    foreach ($equipos as $equipo) {
                        $filas = $this->calcularFilasPorActivo($equipo);

                        foreach ($filas as $filaDep) {
                            fputcsv($file, [
                                $contador++,
                                $this->limpiarTexto($this->nombreSucursal($sucursalActiva, $databaseName)),
                                $filaDep['anio'],
                                $equipo->id,
                                $this->limpiarTexto($equipo->tipoActivo?->nombre ?? $equipo->tipo_equipo ?? 'N/A'),
                                $this->limpiarTexto($equipo->marca?->nombre ?? $equipo->marca_equipo ?? 'N/A'),
                                $this->limpiarTexto($equipo->modelo ?? 'N/A'),
                                $this->limpiarTexto($equipo->serial ?? 'N/A'),
                                $this->limpiarTexto($equipo->usuario?->name ?? 'Disponible / Sin asignar'),
                                $this->limpiarTexto($equipo->ubicacion?->nombre ?? 'N/A'),
                                $this->formatearMoneda($filaDep['valor_inicial']),
                                $this->limpiarTexto($equipo->fecha_adquisicion ?? 'N/A'),
                                $this->limpiarTexto($equipo->vida_util_estimada ?? 'N/A'),
                                $this->formatearMoneda($filaDep['depreciacion_del_anio']),
                                $this->formatearMoneda($filaDep['depreciacion_acumulada']),
                                $this->formatearMoneda($filaDep['valor_en_libros']),
                            ]);
                        }
                    }
                });

            fclose($file);
        };

        return [$callback, $headers];
    }

    public function exportarConcentradoAnualCsv(array $filtros = [])
    {
        $fileName = 'Reporte_Concentrado_Depreciacion_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($filtros) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $databaseName = DB::connection()->getDatabaseName();
            $sucursalActiva = session('sucursal_activa', 'N/A');

            fputcsv($file, [
                'No.',
                'Base / Sucursal',
                'Año',
                'Activos considerados',
                'Valor inicial total',
                'Depreciación del año',
                'Depreciación acumulada',
                'Valor en libros',
            ]);

            $equipos = Equipo::with(['tipoActivo'])
                ->filtrar($filtros)
                ->get();

            $concentrado = $this->calcularConcentradoAnual($equipos);

            $contador = 1;

            foreach ($concentrado as $fila) {
                fputcsv($file, [
                    $contador++,
                    $this->limpiarTexto($this->nombreSucursal($sucursalActiva, $databaseName)),
                    $fila['anio'],
                    $fila['activos'],
                    $this->formatearMoneda($fila['valor_inicial_total']),
                    $this->formatearMoneda($fila['depreciacion_del_anio']),
                    $this->formatearMoneda($fila['depreciacion_acumulada']),
                    $this->formatearMoneda($fila['valor_en_libros']),
                ]);
            }

            fclose($file);
        };

        return [$callback, $headers];
    }

    private function calcularFilasPorActivo($equipo): array
    {
        $valorInicial = $this->normalizarNumero($equipo->valor_inicial);
        $vidaUtil = (int) ($equipo->vida_util_estimada ?? 0);

        if ($valorInicial <= 0 || $vidaUtil <= 0 || empty($equipo->fecha_adquisicion)) {
            return [];
        }

        try {
            $fechaAdquisicion = Carbon::parse($equipo->fecha_adquisicion);
        } catch (\Throwable $e) {
            return [];
        }

        $anioInicio = (int) $fechaAdquisicion->year;
        $depreciacionAnual = $valorInicial / $vidaUtil;
        $depreciacionAcumulada = 0;

        $filas = [];

        for ($i = 0; $i < $vidaUtil; $i++) {
            $anio = $anioInicio + $i;

            $depreciacionAcumulada += $depreciacionAnual;
            $depreciacionAcumulada = min($depreciacionAcumulada, $valorInicial);

            $valorEnLibros = max($valorInicial - $depreciacionAcumulada, 0);

            $filas[] = [
                'anio' => $anio,
                'valor_inicial' => $valorInicial,
                'depreciacion_del_anio' => $depreciacionAnual,
                'depreciacion_acumulada' => $depreciacionAcumulada,
                'valor_en_libros' => $valorEnLibros,
            ];
        }

        return $filas;
    }

    private function calcularConcentradoAnual($equipos): array
    {
        $concentrado = [];

        foreach ($equipos as $equipo) {
            $filas = $this->calcularFilasPorActivo($equipo);

            foreach ($filas as $fila) {
                $anio = $fila['anio'];

                if (! isset($concentrado[$anio])) {
                    $concentrado[$anio] = [
                        'anio' => $anio,
                        'activos' => 0,
                        'valor_inicial_total' => 0,
                        'depreciacion_del_anio' => 0,
                        'depreciacion_acumulada' => 0,
                        'valor_en_libros' => 0,
                    ];
                }

                $concentrado[$anio]['activos']++;
                $concentrado[$anio]['valor_inicial_total'] += $fila['valor_inicial'];
                $concentrado[$anio]['depreciacion_del_anio'] += $fila['depreciacion_del_anio'];
                $concentrado[$anio]['depreciacion_acumulada'] += $fila['depreciacion_acumulada'];
                $concentrado[$anio]['valor_en_libros'] += $fila['valor_en_libros'];
            }
        }

        ksort($concentrado);

        return array_values($concentrado);
    }

    private function normalizarNumero($valor): float
    {
        if ($valor === null || $valor === '') {
            return 0;
        }

        return (float) preg_replace('/[^0-9.]/', '', (string) $valor);
    }

    private function formatearMoneda($valor): string
    {
        return '$' . number_format((float) $valor, 2);
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

        $texto = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', $texto);

        return trim($texto);
    }
}