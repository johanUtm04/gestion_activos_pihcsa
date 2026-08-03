<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MantenimientoVehiculo extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos_vehiculos';

    protected $fillable = [
        'vehiculo_id',
        'usuario_id',
        'tipo_evento',
        'proveedor',
        'kilometraje',
        'fecha_evento',
        'contexto',
        'costo',
        'orden_servicio_path',
        'factura_path',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getOrdenServicioVerUrlAttribute()
    {
        return $this->orden_servicio_path
            ? route('mantenimientos.ver', [$this->id, 'orden_servicio'])
            : null;
    }

    public function getOrdenServicioDescargaUrlAttribute()
    {
        return $this->orden_servicio_path
            ? route('mantenimientos.descargar', [$this->id, 'orden_servicio'])
            : null;
    }

    public function getFacturaVerUrlAttribute()
    {
        return $this->factura_path
            ? route('mantenimientos.ver', [$this->id, 'factura'])
            : null;
    }

    public function getFacturaDescargaUrlAttribute()
    {
        return $this->factura_path
            ? route('mantenimientos.descargar', [$this->id, 'factura'])
            : null;
    }
}