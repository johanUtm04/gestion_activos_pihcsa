<?php

namespace App\Services;

use App\Models\Historial_log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MantenimientoService
{
    public function registrar($equipo, $data)
    {
        $usuarioMantenimiento = User::find($data['usuario_id']);

        /*
        |--------------------------------------------------------------------------
        | Guardar Orden de Servicio
        |--------------------------------------------------------------------------
        */

        $ordenServicioPath = null;

        if (!empty($data['orden_servicio'])) {
            $ordenServicioPath = $data['orden_servicio']->store(
                'mantenimientos/equipos/ordenes_servicio',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Guardar Factura
        |--------------------------------------------------------------------------
        */

        $facturaPath = null;

        if (!empty($data['factura'])) {
            $facturaPath = $data['factura']->store(
                'mantenimientos/equipos/facturas',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar historial
        |--------------------------------------------------------------------------
        */

        return Historial_log::create([
            'activo_id'         => $equipo->id,
            'usuario_accion_id' => Auth::id(),
            'tipo_registro'     => 'MANTENIMIENTO',

            'detalles_json' => [

                'mensaje' => 'Nuevo Mantenimiento agregado',

                'usuario_asignado' => $usuarioMantenimiento->name,

                'rol' => $usuarioMantenimiento->rol ?? 'Técnico',

                /*
                |--------------------------------------------------------------------------
                | Datos adicionales
                |--------------------------------------------------------------------------
                */

                'proveedor' => $data['proveedor'] ?? null,

                'documentos' => [
                    'orden_servicio_path' => $ordenServicioPath,
                    'factura_path'        => $facturaPath,
                ],

                /*
                |--------------------------------------------------------------------------
                | Información mostrada actualmente en historial
                |--------------------------------------------------------------------------
                */

                'cambios' => [

                    'Detalles del Servicio' => [

                        'antes' => 'N/A',

                        'despues' => $this->formatearHtml(
                            $data,
                            $usuarioMantenimiento,
                            $ordenServicioPath,
                            $facturaPath
                        ),
                    ],

                ],

            ],
        ]);
    }

    private function formatearHtml(
        $data,
        $usuario,
        $ordenServicioPath = null,
        $facturaPath = null
    ) {

        $html = "<div class='text-left'>";

        $html .=
            "<strong>Evento:</strong> " .
            e($data['tipo_evento_final']) .
            "<br>";

        $html .=
            "<strong>Técnico:</strong> " .
            e($usuario->name) .
            "<br>";

        $html .=
            "<strong>Fecha:</strong> " .
            e($data['fecha_evento']) .
            "<br>";

        /*
        |--------------------------------------------------------------------------
        | Proveedor
        |--------------------------------------------------------------------------
        */

        if (!empty($data['proveedor'])) {

            $html .=
                "<strong>Proveedor:</strong> " .
                e($data['proveedor']) .
                "<br>";

        }

        /*
        |--------------------------------------------------------------------------
        | Contexto
        |--------------------------------------------------------------------------
        */

        $html .=
            "<strong>Contexto del Evento:</strong> " .
            e($data['contexto'] ?? 'Sin descripción') .
            "<br>";

        /*
        |--------------------------------------------------------------------------
        | Costo
        |--------------------------------------------------------------------------
        */

        $html .=
            "<strong>Costo:</strong> $" .
            number_format($data['costo'] ?? 0, 2) .
            "<br>";

        /*
        |--------------------------------------------------------------------------
        | Orden de servicio
        |--------------------------------------------------------------------------
        */

        if ($ordenServicioPath) {

            $urlOrden = asset(
                'storage/' . $ordenServicioPath
            );

            $html .=
                "<strong>Orden de servicio:</strong> " .
                "<a href='{$urlOrden}' target='_blank'>" .
                "<i class='fas fa-file-alt'></i> Ver documento" .
                "</a><br>";

        }

        /*
        |--------------------------------------------------------------------------
        | Factura
        |--------------------------------------------------------------------------
        */

        if ($facturaPath) {

            $urlFactura = asset(
                'storage/' . $facturaPath
            );

            $html .=
                "<strong>Factura:</strong> " .
                "<a href='{$urlFactura}' target='_blank'>" .
                "<i class='fas fa-file-invoice'></i> Ver factura" .
                "</a><br>";

        }

        $html .= "</div>";

        return $html;
    }
}