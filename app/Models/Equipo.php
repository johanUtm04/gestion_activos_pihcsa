<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Equipo extends Model
{

    protected static function booted(): void
    {
        static::creating(function ($equipo) {
            $equipo->folio = (DB::table('equipos')->max('folio') ?? 0) + 1;
        });
    }

    use HasFactory, SoftDeletes;

    //attach a custom accessor to the model's data
    protected $appends = ['semaforo'];

    protected $casts = [
        'fecha_ultimo_mantenimiento' => 'date',
        'fecha_adquisicion' => 'date',
        'vida_util_estimada' => 'integer',
    ];

    protected $fillable = [
        'marca_equipo','marca_id', 'modelo', 'fecha_ultimo_mantenimiento', 'folio',
        'tipo_equipo','tipo_activo_id', 'serial', 'numero_factura', 'fecha_inicio_uso',
        'sistema_operativo', 'usuario_id', 'ubicacion_id','departamento_perteneciente',
        'valor_inicial','fecha_adquisicion', 'vida_util_estimada', 'motivo_inactivacion'
    ];

    public function getSemaforoAttribute()
    {
        $tipo = $this->tipoActivo;

        $fechaBase = $this->fecha_ultimo_mantenimiento 
            ?? $this->fecha_adquisicion 
            ?? $this->created_at;

        $proximoManto = Carbon::parse($fechaBase)->addMonths($tipo->frecuencia_meses);

        //
        $diasDiferencia = (int) now()->diffInDays($proximoManto, false);

        if (!$tipo || $tipo->frecuencia_meses <= 0) {
                return (object) [
                    'clase' => 'badge-secondary', 
                    'texto' => 'N/A',
                    'dias'  => 'N/A', 
                ];
            }

        if ($diasDiferencia < 0) {
            return (object) [
            'clase' => 'badge-danger',
            'texto' => 'VENCIDO',
            'dias'  => 'N/A', 
            ];
        }
        
        return $diasDiferencia <= 30 
            ? (object) ['clase' => 'badge-warning', 'texto' => 'POR VENCER en ' . $diasDiferencia . ' dias', 'dias'  => $diasDiferencia, ]
            : (object) ['clase' => 'badge-success', 'texto' => 'AL DÍA', 'dias'  => 'N/A'];
    }

    // ----------------------------------------------------
    // RELACIONES
    // ----------------------------------------------------

    public function usuario()    { return $this->belongsTo(User::class, 'usuario_id'); }
    public function ubicacion()  { return $this->belongsTo(Ubicacion::class, 'ubicacion_id'); }
    public function marca()      { return $this->belongsTo(Marca::class, 'marca_id'); }
    public function tipoActivo() { return $this->belongsTo(TipoActivo::class, 'tipo_activo_id'); }
    public function monitores()    { return $this->hasMany(Monitor::class, 'equipo_id'); }
    public function discosDuros()  { return $this->hasMany(DiscoDuro::class, 'equipo_id'); }
    public function rams()         { return $this->hasMany(Ram::class, 'equipo_id'); }
    public function perifericos()  { return $this->hasMany(Periferico::class, 'equipo_id'); }
    public function procesadores() { return $this->hasMany(Procesador::class, 'equipo_id'); }
    public function historials()   { return $this->hasMany(Historial_log::class, 'activo_id'); }


    // ----------------------------------------------------
    // SCOPES (Lógica de filtrado centralizada)
    // ----------------------------------------------------    

    // Query Scope para centralizar todos los filtros
    // Se esta creando una consulta dinamica a medida que avanza
    public function scopeFiltrar(Builder $query, array $filtros)
    {
        // Devuelve el Builder (la consulta). Siempre regresa $query,
        // pero puede modificarlo si se cumple la condición.
        return $query->when(
            
        // Primer parámetro del when():
        // Si $filtros['seccion'] existe y es truthy, se ejecuta el callback.
        // Si es null, vacío, false, etc., NO se ejecuta.
        $filtros['seccion'] ?? null, 

        // Segundo parámetro del when(): un callback (función anónima)
        // Laravel lo ejecuta SOLO si el primer parámetro fue truthy.
        // $q = el Builder actual (la consulta)
        // $busqueda = el valor de $filtros['seccion']
        function ($q, $busqueda) {

            // Se agrega una condición WHERE agrupada (entre paréntesis en SQL)
            // Laravel crea un sub-builder ($sq) para construir el grupo.
            $q->where(function ($sq) use ($busqueda) {

                // Primera condición dentro del grupo:
                // serial LIKE '%valor%'
                $sq->where('serial', 'LIKE', "%$busqueda%")

                    // Segunda condición del grupo:
                    // OR modelo LIKE '%valor%'
                   ->orWhere('modelo', 'LIKE', "%$busqueda%");
            });
        })
        ->when($filtros['ubicacion_id'] ?? null, fn($q, $id) => $q->where('ubicacion_id', $id))
        ->when($filtros['usuario_id'] ?? null, fn($q, $id) => $q->where('usuario_id', $id))
        ->when($filtros['marca_id'] ?? null, fn($q, $id) => $q->where('marca_id', $id))
        ->when($filtros['tipo_activo_id'] ?? null, fn($q, $id) => $q->where('tipo_activo_id', $id))
        ->when($filtros['departamento'] ?? null, fn($q, $dep) => $q->where('departamento_perteneciente', $dep))

        // Ram
        ->when($filtros['ram_capacidad'] ?? null, function ($q, $valor) {
            $q->whereHas('rams', fn($sq) => $sq->where('capacidad_gb', $valor)->where('is_active', 1));
        })
        ->when($filtros['ram_clock'] ?? null, function ($q, $valor) {
            $q->whereHas('rams', fn($sq) => $sq->where('clock_mhz', $valor)->where('is_active', 1));
        })
        ->when($filtros['ram_tipo'] ?? null, function ($q, $valor) {
            $q->whereHas('rams', fn($sq) => $sq->where('tipo_chz', $valor)->where('is_active', 1));
        })

        // Monitor
        ->when($filtros['monitor_marca'] ?? null, function ($q, $marca) {
            $q->whereHas('monitores', fn($sq) => $sq->where('marca', $marca)->where('is_active', 1));
        })
        ->when($filtros['escala_pulgadas'] ?? null, function ($q, $pulgadas) {
            $q->whereHas('monitores', fn($sq) => $sq->where('escala_pulgadas', $pulgadas)->where('is_active', 1));
        })
        ->when($filtros['monitor_interface'] ?? null, function ($q, $interface) {
            $q->whereHas('monitores', fn($sq) => $sq->where('interface', $interface)->where('is_active', 1));
        })

        //Disco Duro
        ->when($filtros['disco_capacidad'] ?? null, function ($q, $capacidad) {
            $q->whereHas('discosDuros', fn($sq) => $sq->where('capacidad', $capacidad)->where('is_active', 1));
        })
        ->when($filtros['disco_tipo'] ?? null, function ($q, $tipo) {
            $q->whereHas('discosDuros', fn($sq) => $sq->where('tipo_hdd_ssd', $tipo)->where('is_active', 1));
        })
        ->when($filtros['disco_interface'] ?? null, function ($q, $interface) {
            $q->whereHas('discosDuros', fn($sq) => $sq->where('interface', $interface)->where('is_active', 1));
        })

        //Procesador
        ->when($filtros['procesador_marca'] ?? null, function ($q, $marca) {
            $q->whereHas('procesadores', fn($sq) => $sq->where('marca', $marca)->where('is_active', 1));
        })

        ->when($filtros['procesador_tipo'] ?? null, function ($q, $tipo) {
            $q->whereHas('procesadores', fn($sq) => $sq->where('descripcion_tipo', $tipo)->where('is_active', 1));
        })
        ->when($filtros['procesador_ghz'] ?? null, function ($q, $ghz) {
            $q->whereHas('procesadores', fn($sq) => $sq->where('clock_ghz', $ghz)->where('is_active', 1));
        })
        
        //Manejo de Inactivos:
        ->when(($filtros['filter'] ?? null) == 'inactivos', fn($q) => $q->onlyTrashed());
    }

    public function getEstadoMantenimientoAttribute()
    {
        $semaforo = $this->semaforo;

        if ($semaforo->dias > 4000) {
            return "Realizar mantenimiento mensual (Pendiente de programar)";
        }

        if ($semaforo->dias <= 0) {
            return "Realizar mantenimiento mensual (¡VENCIDO!)";
        }

        return "Realizar mantenimiento mensual (Faltan {$semaforo->dias} días)";
    }

}
