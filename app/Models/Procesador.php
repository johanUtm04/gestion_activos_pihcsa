<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procesador extends Model
{
    use HasFactory;
    protected $table = 'procesadores';
    protected $guarded = ['id'];

    public function equipos() 
    {
        return $this->belongsTo(Equipo::class, 'equipo_id'); 
    }
}