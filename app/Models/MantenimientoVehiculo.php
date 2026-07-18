<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MantenimientoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos_vehiculos';

    protected $fillable = [
        'vehiculo_id',
        'usuario_id',
        'tipo_evento',
        'kilometraje',
        'fecha_evento',
        'contexto',
        'costo',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}