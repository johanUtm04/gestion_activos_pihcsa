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

        $data = $this->getFilterData();

        $tasas = Tasa::all();

        return view('depreciacion.index', compact('equipos', 'tasas'))->with($data);
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
                $faltante = !$inpcAdq ? "Adquisición ({$fechaAdq->format('m/Y')})" : "Mitad Periodo ({$mesCronologicoMitad}/{$anioActual})";
                return response()->json([
                    'error' => "Falta índice INPC para: " . $faltante
                ], 404);
            }

            return response()->json([
                'tasa' => (float)$request->tasa_id, 
                'inpc_adq' => (float)$inpcAdq->valor,
                'inpc_mitad' => (float)$inpcMitad->valor,
                'meses_uso' => $mesesDeUso
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }
}