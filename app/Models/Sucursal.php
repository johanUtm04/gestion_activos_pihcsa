<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Importante
    |--------------------------------------------------------------------------
    | Esta tabla siempre vive en la base principal:
    | gestion_activos_pihcsa_v2
    */
    protected $connection = 'mysql';

    protected $table = 'sucursales';

    protected $fillable = [
        'clave',
        'nombre',
        'database_name',
        'estatus',
        'descripcion',
    ];

    public function scopeActivas($query)
    {
        return $query->where('estatus', 'activo');
    }
}