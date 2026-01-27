<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ram extends Model
{
    use HasFactory;
    protected $table = 'rams';
    protected $guarded = ['id'];

    public function equipos() 
    {
        return $this->belongsTo(Equipo::class, 'equipo_id'); 
    }
}