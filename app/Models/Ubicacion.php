<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    
    // CRÍTICO: Debe apuntar a la tabla correcta en la DB.
    protected $table = 'ubicaciones'; 
    protected $guarded = ['id'];

    /**
     * Relación 1:N (Una ubicación tiene muchos equipos)
     */
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'ubicacion_id'); 
    }
}