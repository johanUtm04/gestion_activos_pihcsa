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

    protected $appends = [
        'estatus_mantenimiento',
        'proximo_mantenimiento',
        'dias_para_mantenimiento',
        'indicadores_operativos',
    ];

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
        'cuenta_contable',
        'tipo_combustible',
        'valor_inicial',
        'fecha_adquisicion',
        'vida_util_estimada',
        'fecha_ultimo_mantenimiento',
        'is_active',
        'motivo_inactivacion',
    ];

    /**
     * Aplica filtro automático por empresa / sucursal operativa.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope);
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(CatTipoVehiculo::class, 'tipo_vehiculo_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function documentacion()
    {
        return $this->hasOne(VehiculoDocumentacion::class, 'vehiculo_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Mantenimiento preventivo
    |--------------------------------------------------------------------------
    */

    public function mantenimientos()
    {
        return $this->hasMany(MantenimientoVehiculo::class, 'vehiculo_id')->orderBy('fecha_evento', 'desc');
    }

    public function getProximoMantenimientoAttribute()
    {
        if (
            empty($this->fecha_ultimo_mantenimiento) ||
            ! $this->tipoVehiculo ||
            empty($this->tipoVehiculo->frecuencia_meses) ||
            (int) $this->tipoVehiculo->frecuencia_meses <= 0
        ) {
            return null;
        }

        try {
            return Carbon::parse($this->fecha_ultimo_mantenimiento)
                ->addMonths((int) $this->tipoVehiculo->frecuencia_meses)
                ->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getDiasParaMantenimientoAttribute()
    {
        if (empty($this->proximo_mantenimiento)) {
            return null;
        }

        try {
            return now()->startOfDay()->diffInDays(
                Carbon::parse($this->proximo_mantenimiento)->startOfDay(),
                false
            );
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Semáforo principal del vehículo.
     *
     * verde    = al día
     * amarillo = próximo a vencer
     * rojo     = vencido, sin dato crítico o inactivo
     */
    public function getEstatusMantenimientoAttribute()
    {
        if (! $this->is_active) {
            return 'rojo';
        }

        if (
            empty($this->fecha_ultimo_mantenimiento) ||
            ! $this->tipoVehiculo ||
            empty($this->tipoVehiculo->frecuencia_meses) ||
            (int) $this->tipoVehiculo->frecuencia_meses <= 0
        ) {
            return 'rojo';
        }

        $dias = $this->dias_para_mantenimiento;

        if ($dias === null) {
            return 'rojo';
        }

        if ($dias < 0) {
            return 'rojo';
        }

        if ($dias <= 30) {
            return 'amarillo';
        }

        return 'verde';
    }

    /*
    |--------------------------------------------------------------------------
    | Indicadores operativos para el Command Center
    |--------------------------------------------------------------------------
    */

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
            empty($this->fecha_ultimo_mantenimiento) ||
            ! $this->tipoVehiculo ||
            empty($this->tipoVehiculo->frecuencia_meses) ||
            (int) $this->tipoVehiculo->frecuencia_meses <= 0
        ) {
            return [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-tools',
                'score' => 0,
                'status' => 'Sin dato',
                'detail' => 'No hay frecuencia o último mantenimiento registrado.',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        }

        try {
            $ultimo = Carbon::parse($this->fecha_ultimo_mantenimiento)->startOfDay();
            $proximo = $ultimo->copy()->addMonths((int) $this->tipoVehiculo->frecuencia_meses)->startOfDay();
            $hoy = Carbon::now()->startOfDay();
        } catch (\Throwable $e) {
            return [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-tools',
                'score' => 0,
                'status' => 'Error',
                'detail' => 'La fecha de mantenimiento no tiene un formato válido.',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        }

        $diasTotales = max($ultimo->diffInDays($proximo), 1);
        $diasRestantes = $hoy->diffInDays($proximo, false);

        $score = max(0, min(100, round(($diasRestantes / $diasTotales) * 100)));

        if ($diasRestantes < 0) {
            return [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-tools',
                'score' => 0,
                'status' => 'Vencido',
                'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s).',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        }

        if ($diasRestantes <= 30) {
            return [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-clock',
                'score' => $score,
                'status' => 'Próximo',
                'detail' => 'Vence en ' . $diasRestantes . ' día(s).',
                'class' => 'bg-warning',
                'badge' => 'badge-warning',
            ];
        }

        return [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-check-circle',
            'score' => $score,
            'status' => 'Al día',
            'detail' => 'Vence en ' . $diasRestantes . ' día(s).',
            'class' => 'bg-success',
            'badge' => 'badge-success',
        ];
    }

    private function indicadorSeguro(): array
    {
        if (! $this->documentacion || empty($this->documentacion->vigencia_seguro)) {
            return [
                'label' => 'Seguro vehicular',
                'icon' => 'fa-shield-alt',
                'score' => 0,
                'status' => 'Sin dato',
                'detail' => 'No hay vigencia de seguro registrada.',
                'class' => 'bg-secondary',
                'badge' => 'badge-secondary',
            ];
        }

        try {
            $vigencia = Carbon::parse($this->documentacion->vigencia_seguro)->startOfDay();
            $diasRestantes = Carbon::now()->startOfDay()->diffInDays($vigencia, false);
        } catch (\Throwable $e) {
            return [
                'label' => 'Seguro vehicular',
                'icon' => 'fa-shield-alt',
                'score' => 0,
                'status' => 'Error',
                'detail' => 'La vigencia del seguro no tiene un formato válido.',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        }

        $score = max(0, min(100, round(($diasRestantes / 365) * 100)));

        if ($diasRestantes < 0) {
            return [
                'label' => 'Seguro vehicular',
                'icon' => 'fa-shield-alt',
                'score' => 0,
                'status' => 'Vencido',
                'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s).',
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
                'detail' => 'Vence en ' . $diasRestantes . ' día(s).',
                'class' => 'bg-warning',
                'badge' => 'badge-warning',
            ];
        }

        return [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => $score,
            'status' => 'Vigente',
            'detail' => 'Vence en ' . $diasRestantes . ' día(s).',
            'class' => 'bg-success',
            'badge' => 'badge-success',
        ];
    }

    private function indicadorVidaUtil(): array
    {
        if (
            empty($this->fecha_adquisicion) ||
            empty($this->vida_util_estimada) ||
            (int) $this->vida_util_estimada <= 0
        ) {
            return [
                'label' => 'Vida útil del activo',
                'icon' => 'fa-chart-line',
                'score' => 0,
                'status' => 'Sin dato',
                'detail' => 'No hay fecha de adquisición o vida útil registrada.',
                'class' => 'bg-secondary',
                'badge' => 'badge-secondary',
            ];
        }

        try {
            $fechaAdquisicion = Carbon::parse($this->fecha_adquisicion)->startOfDay();
            $finVidaUtil = $fechaAdquisicion->copy()->addYears((int) $this->vida_util_estimada)->startOfDay();
            $hoy = Carbon::now()->startOfDay();
        } catch (\Throwable $e) {
            return [
                'label' => 'Vida útil del activo',
                'icon' => 'fa-chart-line',
                'score' => 0,
                'status' => 'Error',
                'detail' => 'La fecha de adquisición no tiene un formato válido.',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        }

        $mesesTotales = max(((int) $this->vida_util_estimada) * 12, 1);
        $mesesRestantes = $hoy->diffInMonths($finVidaUtil, false);

        $score = max(0, min(100, round(($mesesRestantes / $mesesTotales) * 100)));

        if ($mesesRestantes < 0) {
            return [
                'label' => 'Vida útil del activo',
                'icon' => 'fa-chart-line',
                'score' => 0,
                'status' => 'Agotada',
                'detail' => 'Superó su vida útil estimada.',
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
                'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es).',
                'class' => 'bg-warning',
                'badge' => 'badge-warning',
            ];
        }

        return [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => $score,
            'status' => 'Vigente',
            'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es).',
            'class' => 'bg-success',
            'badge' => 'badge-success',
        ];
    }
}