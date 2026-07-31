<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function equiposCount(): int
    {
        return \App\Models\Equipo::where('departamento_perteneciente', $this->nombre)->count();
    }
}