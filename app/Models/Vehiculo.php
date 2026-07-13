<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\EmpresaScope;
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
        'pedimento',
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
     * Centralizado en la clase EmpresaScope para mantener el modelo limpio.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
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


    public function getIndicadoresOperativosAttribute()
{
    return [
        $this->indicadorMantenimiento(),
        $this->indicadorSeguro(),
        $this->indicadorVidaUtil(),
    ];
}

private function indicadorMantenimiento(): array
{
    if (
        !$this->fecha_ultimo_mantenimiento ||
        !$this->tipoVehiculo ||
        !$this->tipoVehiculo->frecuencia_meses ||
        $this->tipoVehiculo->frecuencia_meses <= 0
    ) {
        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay frecuencia o último mantenimiento registrado',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    }

    $ultimo = Carbon::parse($this->fecha_ultimo_mantenimiento);
    $proximo = $ultimo->copy()->addMonths($this->tipoVehiculo->frecuencia_meses);
    $hoy = Carbon::now();

    $diasTotales = max($ultimo->diffInDays($proximo), 1);
    $diasRestantes = $hoy->diffInDays($proximo, false);

    $score = max(0, min(100, round(($diasRestantes / $diasTotales) * 100)));

    if ($diasRestantes < 0) {
        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => 0,
            'status' => 'Vencido',
            'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s)',
            'class' => 'bg-danger',
            'badge' => 'badge-danger',
        ];
    }

    if ($diasRestantes <= 30) {
        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => $score,
            'status' => 'Próximo',
            'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
            'class' => 'bg-warning',
            'badge' => 'badge-warning',
        ];
    }

    return [
        'label' => 'Mantenimiento preventivo',
        'icon' => 'fa-tools',
        'score' => $score,
        'status' => 'Al día',
        'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
        'class' => 'bg-success',
        'badge' => 'badge-success',
    ];
}

private function indicadorSeguro(): array
{
    if (!$this->documentacion || !$this->documentacion->vigencia_seguro) {
        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay vigencia de seguro registrada',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    }

    $vigencia = Carbon::parse($this->documentacion->vigencia_seguro);
    $diasRestantes = Carbon::now()->diffInDays($vigencia, false);

    $score = max(0, min(100, round(($diasRestantes / 365) * 100)));

    if ($diasRestantes < 0) {
        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => 0,
            'status' => 'Vencido',
            'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s)',
            'class' => 'bg-danger',
            'badge' => 'badge-danger',
        ];
    }

    if ($diasRestantes <= 30) {
        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => $score,
            'status' => 'Por vencer',
            'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
            'class' => 'bg-warning',
            'badge' => 'badge-warning',
        ];
    }

    return [
        'label' => 'Seguro vehicular',
        'icon' => 'fa-shield-alt',
        'score' => $score,
        'status' => 'Vigente',
        'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
        'class' => 'bg-success',
        'badge' => 'badge-success',
    ];
}

private function indicadorVidaUtil(): array
{
    if (!$this->fecha_adquisicion || !$this->vida_util_estimada || $this->vida_util_estimada <= 0) {
        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay fecha de adquisición o vida útil registrada',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    }

    $fechaAdquisicion = Carbon::parse($this->fecha_adquisicion);
    $finVidaUtil = $fechaAdquisicion->copy()->addMonths($this->vida_util_estimada);
    $hoy = Carbon::now();

    $mesesTotales = max($this->vida_util_estimada, 1);
    $mesesRestantes = $hoy->diffInMonths($finVidaUtil, false);

    $score = max(0, min(100, round(($mesesRestantes / $mesesTotales) * 100)));

    if ($mesesRestantes < 0) {
        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => 0,
            'status' => 'Agotada',
            'detail' => 'Superó su vida útil estimada',
            'class' => 'bg-danger',
            'badge' => 'badge-danger',
        ];
    }

    if ($mesesRestantes <= 6) {
        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => $score,
            'status' => 'Finalizando',
            'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es)',
            'class' => 'bg-warning',
            'badge' => 'badge-warning',
        ];
    }

    return [
        'label' => 'Vida útil del activo',
        'icon' => 'fa-chart-line',
        'score' => $score,
        'status' => 'Vigente',
        'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es)',
        'class' => 'bg-success',
        'badge' => 'badge-success',
    ];
}
}