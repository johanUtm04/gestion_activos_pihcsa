<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActivo extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'tipo_activo_id');
    }
}
