<?php

namespace App\Http\Controllers;

use App\Models\{Equipo, Ubicacion, User, Marca, TipoActivo, Tasa, Inpc};
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DepreciacionController extends Controller
{
    public function index(Request $request): View
    {
        $equipos = Equipo::with(['usuario', 'ubicacion', 'marca', 'tipoActivo'])
            ->filtrar($request->all())
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Concentrado anual de depreciación
        |--------------------------------------------------------------------------
        | Usamos una consulta aparte sin paginar para calcular el resumen global.
        | Respeta los mismos filtros de pantalla.
        */
        $equiposParaConcentrado = Equipo::with(['tipoActivo'])
            ->filtrar($request->all())
            ->get();

        $concentradoAnual = $this->calcularConcentradoAnual($equiposParaConcentrado);

        $data = $this->getFilterData();

        $tasas = Tasa::all();

        return view('depreciacion.index', compact(
            'equipos',
            'tasas',
            'concentradoAnual'
        ))->with($data);
    }

    private function getFilterData()
    {
        return [
            'usuarios'    => User::all(),
            'ubicaciones' => Ubicacion::all(),
            'marcas'      => Marca::all(),
            'tipos'       => TipoActivo::all(),
        ];
    }

    private function calcularConcentradoAnual($equipos): array
    {
        $concentrado = [];

        foreach ($equipos as $equipo) {
            $valorInicial = $this->normalizarNumero($equipo->valor_inicial);
            $vidaUtil = (int) ($equipo->vida_util_estimada ?? 0);

            if ($valorInicial <= 0 || $vidaUtil <= 0 || empty($equipo->fecha_adquisicion)) {
                continue;
            }

            try {
                $fechaAdquisicion = Carbon::parse($equipo->fecha_adquisicion);
            } catch (\Throwable $e) {
                continue;
            }

            $anioInicio = (int) $fechaAdquisicion->year;
            $depreciacionAnual = $valorInicial / $vidaUtil;
            $depreciacionAcumuladaActivo = 0;

            for ($i = 0; $i < $vidaUtil; $i++) {
                $anio = $anioInicio + $i;

                $depreciacionAcumuladaActivo += $depreciacionAnual;
                $valorEnLibrosActivo = max($valorInicial - $depreciacionAcumuladaActivo, 0);

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
                $concentrado[$anio]['valor_inicial_total'] += $valorInicial;
                $concentrado[$anio]['depreciacion_del_anio'] += $depreciacionAnual;
                $concentrado[$anio]['depreciacion_acumulada'] += min($depreciacionAcumuladaActivo, $valorInicial);
                $concentrado[$anio]['valor_en_libros'] += $valorEnLibrosActivo;
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

    public function getFiscalData(Request $request)
    {
        try {
            // 1. Validar fechas recibidas
            $fechaAdq = Carbon::parse($request->fecha_adq);
            $fechaUso = Carbon::parse($request->fecha_uso);
            $anioActual = $fechaUso->year;

            // 2. Obtener INPC Mes de Adquisición
            $inpcAdq = Inpc::where('anio', $fechaAdq->year)
                           ->where('mes', $fechaAdq->month)
                           ->first();

            // 3. Lógica LISR: Determinar mes de la 1ra mitad del periodo de uso
            $mesesDeUso = 12 - $fechaUso->month + 1;
            $mesMitadRelativo = ceil($mesesDeUso / 2);
            $mesCronologicoMitad = $fechaUso->month + $mesMitadRelativo - 1;

            $inpcMitad = Inpc::where('anio', $anioActual)
                             ->where('mes', $mesCronologicoMitad)
                             ->first();

            // 4. Verificar si existen los datos para evitar el error del Alert
            if (!$inpcAdq || !$inpcMitad) {
                $faltante = !$inpcAdq
                    ? "Adquisición ({$fechaAdq->format('m/Y')})"
                    : "Mitad Periodo ({$mesCronologicoMitad}/{$anioActual})";

                return response()->json([
                    'error' => "Falta índice INPC para: " . $faltante
                ], 404);
            }

            return response()->json([
                'tasa' => (float) $request->tasa_id,
                'inpc_adq' => (float) $inpcAdq->valor,
                'inpc_mitad' => (float) $inpcMitad->valor,
                'meses_uso' => $mesesDeUso
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }
}