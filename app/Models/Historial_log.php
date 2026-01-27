<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Historial_log extends Model
{
    use HasFactory;
    // CRÍTICO: Debe apuntar a la tabla correcta en la DB.
    protected $table = 'historiales_log';

    protected $fillable = [
        'activo_id',
        'usuario_accion_id',
        'tipo_registro',
        'detalles_json',
    ];

    //CLAVE PARA JSON
    protected $casts = [
        'detalles_json' => 'array',
    ];
    // === RECEPCIÓN DE RELACIONES (belongsTo) ===

    /**
     * Relación N:1 (Muchos logs pertenecen a un Equipo)
     */
    public function equipo()
    {
        // belongsTo(Modelo_Padre, FK_En_Esta_Tabla)
        return $this->belongsTo(Equipo::class, 'activo_id');
    }

    /**
     * Relación N:1 (Muchos logs fueron registrados por un Usuario)
     */
    public function usuario()
    {
        // belongsTo(Modelo_Padre, FK_En_Esta_Tabla)
        return $this->belongsTo(User::class, 'usuario_accion_id');
    }
}