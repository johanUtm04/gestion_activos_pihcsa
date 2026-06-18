<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoDocumentacion extends Model
{
    use HasFactory;

    // Vinculamos a la tabla secundaria de papeleo
    protected $table = 'vehiculo_documentacion';

    protected $fillable = [
        'vehiculo_id',
        'no_poliza_seguro',
        'vigencia_seguro',
        'tarjeta_circulacion'
    ];

    // Relación inversa: Esta documentación pertenece a un vehículo único
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
}