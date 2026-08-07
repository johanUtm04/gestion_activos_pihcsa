<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'rfc',
        'activo'
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'empresa_id');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'empresa_id');
    }
}