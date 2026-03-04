<?php
namespace App\Services;

use App\Models\Equipo;
use App\Models\Historial_log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MantenimientoService{
    public function registrar($equipo, $data)
    {
        $usuarioMantenimiento = User::find($data['usuario_id']);
        return Historial_log::create([
                    'activo_id'         => $equipo->id,
                    'usuario_accion_id' => Auth::id(),
                    'tipo_registro'     => 'MANTENIMIENTO',
                    'detalles_json'     => [
                        'mensaje' => 'Nuevo Mantenimiento agregado',
                        'usuario_asignado' => $usuarioMantenimiento->name,
                        'rol' => $usuarioMantenimiento->rol ?? 'Técnico',
                        'cambios' => [
                            'Detalles del Servicio' => [
                                'antes'   => 'N/A',
                                'despues' => $this->formatearHtml($data, $usuarioMantenimiento)
                            ]
                        ]
                    ]
                ]);
    }

    private function formatearHtml($data, $usuario)
    {
        return "<div class='text-left'>" .
               "<strong>Evento:</strong> {$data['tipo_evento_final']}<br>" .
               "<strong>Técnico:</strong> {$usuario->name}<br>" .
               "<strong>Fecha:</strong> {$data['fecha_evento']}<br>" .
               "<strong>Contexto del Evento:</strong> {$data['contexto']}<br>" .
               "<strong>Costo:</strong> $" . number_format($data['costo'] ?? 0, 2) .
               "</div>";
    }



}