<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscoDuro extends Model
{
    use HasFactory;
    protected $table = 'discos_duros';

    protected $fillable = [
        'equipo_id',
        'capacidad',
        'tipo_hdd_ssd',
        'interface',
        'modelo',
        'serial'
    ];
    protected $guarded = ['id'];
    
    public function equipos() 
    {
        return $this->belongsTo(Equipo::class, 'equipo_id'); 
    } 
}