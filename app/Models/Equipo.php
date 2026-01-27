<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Asegúrate de incluir todos los modelos necesarios
use App\Models\User; 
use App\Models\Ubicacion; 
use App\Models\Monitor;
use App\Models\Disco_Duro; 
use App\Models\Ram;
use App\Models\Periferico;
use App\Models\Procesador;
use App\Models\Historial_log;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory; 

    //Campos asignables, es decir, los campos que se pueden llamar de manera masiva
    protected $fillable = [
        'marca_equipo','marca_id',
        'tipo_equipo','tipo_activo_id',
        'serial',
        'sistema_operativo',
        'usuario_id',
        'ubicacion_id',
        'valor_inicial' ,
        'fecha_adquisicion',
        'vida_util_estimada',
    ];

    // ----------------------------------------------------
    // RELACIONES belongsTo (Este Equipo PERTENECE a uno)
    // ----------------------------------------------------

    // 1. Usuario (Acceso: $equipo->usuario->name)
    public function usuario() 
    { 
        // Busca en la tabla 'users' usando 'usuario_id' de esta tabla
        return $this->belongsTo(User::class, 'usuario_id'); 
    }

    // 2. Ubicación (Acceso: $equipo->ubicacion->nombre)
    public function ubicacion() 
    { 
        // Busca en la tabla 'ubicaciones' usando 'ubicacion_id' de esta tabla
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id'); 
    }

    // ----------------------------------------------------
    // RELACIONES hasMany (Este Equipo TIENE muchos - 1:N)
    // ----------------------------------------------------

    // Estas relaciones funcionan asumiendo que el campo 'equipo_id' está en las tablas hijas.
    
    public function monitores() 
    { 
        return $this->hasMany(Monitor::class, 'equipo_id'); 
    }

    public function discosDuros() 
    { 
        return $this->hasMany(DiscoDuro::class, 'equipo_id'); 
    }
    
    public function rams() 
    { 
        return $this->hasMany(Ram::class, 'equipo_id'); 
    }

    public function perifericos()
    {
        return $this->hasMany(Periferico::class, 'equipo_id');
    }

    public function procesadores()
    {
        return $this->hasMany(Procesador::class, 'equipo_id');
    }

    public function historials() {
        return $this->hasMany(Historial_log::class, 'activo_id');
    }

    // Relación con Marca
    public function marca()
    {
        // Un equipo "pertenece a" una marca
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    // Relación con Tipo de Activo
    public function tipoActivo()
    {
        // Un equipo "pertenece a" un tipo de activo
        return $this->belongsTo(TipoActivo::class, 'tipo_activo_id');
    }

}
