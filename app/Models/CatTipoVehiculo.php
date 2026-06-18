<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatTipoVehiculo extends Model
{
    use HasFactory;

    // Vinculamos explícitamente a la tabla del catálogo
    protected $table = 'cat_tipo_vehiculos';

    protected $fillable = [
        'nombre',
        'frecuencia_meses'
    ];

    // Relación inversa: Un tipo de vehículo tiene muchos vehículos asignados
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'tipo_vehiculo_id');
    }
}