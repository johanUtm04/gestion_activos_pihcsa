<?php

namespace App\Services;

use App\Models\Historial_log;
use App\Models\HistorialLog;

class AuditService
{
    /**
     * Registra una acción en el historial
     */
    // public static function log(
    //     string $tipoRegistro,
    //     ?int $activoId = null,
    //     array $detalles = []
    // ): void {
    //     Historial_log::create([
    //         'activo_id' => $activoId,
    //         'usuario_accion_id' => auth()->id(),
    //         'tipo_registro' => $tipoRegistro,
    //         'detalles_json' => $detalles,
    //     ]);
    // }
}
