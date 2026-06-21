<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Importación necesaria para el Scope
use Carbon\Carbon;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'empresa_id',
        'tipo_vehiculo_id',
        'marca_id',
        'usuario_id',
        'ubicacion_id',
        'modelo',
        'anio',
        'placas',
        'no_serie',
        'no_motor',
        'cilindros',
        'tipo_combustible',
        'valor_inicial',
        'fecha_adquisicion',
        'vida_util_estimada',
        'fecha_ultimo_mantenimiento',
        'is_active',
        'motivo_inactivacion'
    ];

    /**
     * El "booted" aplica filtros automáticos de forma invisible.
     * No altera tus consultas manuales ni rompe vistas actuales.
     */
    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (session()->has('empresa_id')) {
                $builder->where('empresa_id', session('empresa_id'));
            }
        });
    }

    // Relación con la Empresa Maestra (Nueva)
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Relación con el Catálogo de Tipos
    public function tipoVehiculo()
    {
        return $this->belongsTo(CatTipoVehiculo::class, 'tipo_vehiculo_id');
    }

    // Relación con Marcas
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    // Relación con el Usuario Asignado
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con la Ubicación
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    // Relación con su Documentación Legal
    public function documentacion()
    {
        return $this->hasOne(VehiculoDocumentacion::class, 'vehiculo_id');
    }

    /**
     * Lógica de Semáforo para Mantenimientos Preventivos
     * Retorna: 'verde', 'amarillo' o 'rojo'
     */
    public function getEstatusMantenimientoAttribute()
    {
        if (!$this->fecha_ultimo_mantenimiento || !$this->tipoVehiculo || $this->tipoVehiculo->frecuencia_meses <= 0) {
            return 'verde'; 
        }

        $ultimo = Carbon::parse($this->fecha_ultimo_mantenimiento);
        $proximo = $ultimo->addMonths($this->tipoVehiculo->frecuencia_meses);
        $hoy = Carbon::now();

        if ($hoy->greaterThanOrEqualTo($proximo)) {
            return 'rojo'; // Ya venció el plazo
        }

        // Si falta un mes o menos para llegar a la fecha límite -> Amarillo
        if ($hoy->diffInMonths($proximo, false) <= 1) {
            return 'amarillo';
        }

        return 'verde';
    }
}