<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
	protected $fillable = ['nombre'];
	
	public function equipos()
    {
        return $this->hasMany(Equipo::class, 'marca_id');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'marca_id');
    }

}
