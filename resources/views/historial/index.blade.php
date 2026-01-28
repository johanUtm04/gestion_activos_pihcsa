@extends('adminlte::page')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ENCABEZADO GLOBAL --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="text-dark font-weight-bold mb-0">
                <i class="fas fa-clipboard-check text-primary mr-2"></i> Auditoría de Activos
            </h3>
            <p class="text-muted small mb-0">Trazabilidad completa de hardware y movimientos</p>
        </div>
        <div class="col-md-6 text-right">
            <span class="badge badge-white shadow-sm border px-3 py-2">
                <i class="fas fa-microchip text-primary mr-1"></i> Total Equipos: {{ $equipos->total() }}
            </span>
        </div>
    </div>

    {{-- LEYENDA DE TIPOS DE REGISTRO --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-white" style="border-radius: 12px;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <span class="text-muted small font-weight-bold mr-3 text-uppercase"><i class="fas fa-info-circle mr-1"></i> Tipos de Evento:</span>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="mr-3 d-flex align-items-center">
                                <span class="badge badge-success rounded-circle p-1 mr-2"><i class="fas fa-plus-circle" style="width:12px"></i></span>
                                <small class="text-dark font-weight-bold">Creación</small>
                            </div>
                            <div class="mr-3 d-flex align-items-center">
                                <span class="badge badge-warning text-white rounded-circle p-1 mr-2"><i class="fas fa-sync-alt" style="width:12px"></i></span>
                                <small class="text-dark font-weight-bold">Actualización</small>
                            </div>
                            <div class="mr-3 d-flex align-items-center">
                                <span class="badge badge-info text-white rounded-circle p-1 mr-2"><i class="fas fa-tools" style="width:12px"></i></span>
                                <small class="text-dark font-weight-bold">Mantenimiento</small>
                            </div>
                            <div class="mr-3 d-flex align-items-center">
                                <span class="badge rounded-circle p-1 mr-2 text-white" style="background-color: #fd7e14;"><i class="fas fa-memory" style="width:12px"></i></span>
                                <small class="text-dark font-weight-bold">Componentes</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-danger rounded-circle p-1 mr-2"><i class="fas fa-trash-alt" style="width:12px"></i></span>
                                <small class="text-dark font-weight-bold">Eliminación</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($equipos as $equipo)
    <div class="card mb-3 shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        
        {{-- HEADER DEL EQUIPO --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3" 
             style="cursor: pointer; border-left: 6px solid {{ $equipo->tipo_equipo == 'Laptop' ? '#007bff' : '#6f42c1' }};" 
             data-toggle="collapse" 
             data-target="#collapseEquipo{{ $equipo->id }}">
            
            <div class="d-flex align-items-center">
                <div class="icon-box mr-3 shadow-sm d-flex align-items-center justify-content-center {{ $equipo->tipo_equipo == 'Laptop' ? 'bg-primary-soft' : 'bg-purple-soft' }}" 
                     style="width: 50px; height: 50px; border-radius: 12px;">
                    <i class="fas {{ $equipo->tipo_equipo == 'Laptop' ? 'fa-laptop text-primary' : 'fa-desktop text-purple' }} fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0 font-weight-bold text-dark">{{ $equipo->nombre_equipo ?? 'Equipo sin nombre' }}</h6>
                    <div class="d-flex gap-2 mt-1">
                        <span class="badge badge-light border text-muted px-2 mr-2">SN: {{ $equipo->serie ?? $equipo->id }}</span>
                        <small class="text-muted"><i class="fas fa-user-circle mr-1"></i> {{ $equipo->usuario->name ?? 'Sin asignar' }}</small>
                    </div>
                </div>
            </div>
            
            <div class="text-right d-none d-md-block">
                <div class="mb-1">
                    <span class="badge badge-pill badge-primary-soft text-primary px-3">
                        {{ $equipo->historials->count() }} Eventos
                    </span>
                </div>
                <i class="fas fa-chevron-down text-gray-300 transition-icon"></i>
            </div>
        </div>

        {{-- CUERPO COLAPSABLE --}}
        <div id="collapseEquipo{{ $equipo->id }}" class="collapse {{ request('equipo_id') == $equipo->id ? 'show' : '' }}">
            <div class="card-body bg-light p-0">
                <div class="p-4 scroll-custom" style="max-height: 550px; overflow-y: auto;">
                    <div class="timeline-v2">
                        @php $logs = $equipo->historials()->latest()->get(); @endphp

                        @forelse($logs as $log)
                            @php 
                                $detalles = $log->detalles_json;
                                $tipoSlug = \Illuminate\Support\Str::slug($log->tipo_registro);
                                
                                $config = [
                                    'creacion'         => ['bg' => 'bg-success', 'icon' => 'fa-plus-circle'],
                                    'actualizacion'    => ['bg' => 'bg-warning', 'icon' => 'fa-sync-alt'],
                                    'eliminacion'      => ['bg' => 'bg-danger',  'icon' => 'fa-trash-alt'],
                                    'mantenimiento'    => ['bg' => 'bg-info',    'icon' => 'fa-tools'],
                                    'componente-extra' => ['bg' => 'bg-orange',  'icon' => 'fa-memory'],
                                    'inactivacion'     => ['bg' => 'bg-danger',  'icon' => 'fa-power-off'],
                                    'activacion'       => ['bg' => 'bg-success', 'icon' => 'fa-bolt'], 
                                    'estado-componente'=> ['bg' => 'bg-purple',  'icon' => 'fa-microchip'],
                                ][$tipoSlug] ?? ['bg' => 'bg-secondary', 'icon' => 'fa-dot-circle'];
                            @endphp

                            <div class="log-card mb-4 bg-white shadow-xs">
                                <div class="log-header p-3 d-flex justify-content-between align-items-center border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="{{ $config['bg'] }} text-white rounded-circle mr-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px;">
                                            <i class="fas {{ $config['icon'] }} style="font-size: 12px;"></i>
                                        </div>
                                        <span class="font-weight-bold text-dark mr-2">{{ $log->tipo_registro }}</span>
                                        <small class="text-muted border-left pl-2">{{ $log->created_at->format('d M, Y • H:i') }}</small>
                                    </div>
                                    <small class="badge badge-light text-muted font-weight-normal">{{ $log->created_at->diffForHumans() }}</small>
                                </div>

                                <div class="p-3">
                                    @if(isset($detalles['cambios']))
                                        <table class="table table-sm table-borderless mb-0">
                                            @foreach($detalles['cambios'] as $campo => $valor)
                                                <tr class="align-baseline">
                                                    <td class="text-muted small font-weight-bold pt-2" style="width: 25%">
                                                        <i class="fas fa-caret-right text-primary mr-1"></i> {{ Str::headline($campo) }}
                                                    </td>
                                                    <td class="pt-1">
                                                        <div class="d-flex align-items-center flex-wrap">
                                                            @if($tipoSlug !== 'creacion' && ($valor['antes'] ?? 'N/A') !== 'N/A')
                                                                <span class="badge border text-danger bg-light text-decoration-line-through mr-2 px-2 py-1">
                                                                    {{ $valor['antes'] }}
                                                                </span>
                                                                <i class="fas fa-long-arrow-alt-right text-muted mr-2"></i>
                                                            @endif
                                                            
                                                            {{-- MOTOR DE RENDERIZADO DE TABLITAS --}}
                                                            @if(Str::contains($valor['despues'], '|'))
                                                                <div class="bg-light border rounded-lg p-2 mt-1 shadow-xs w-100" style="max-width: 350px;">
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        @foreach(explode('|', $valor['despues']) as $info)
                                                                            @php $partes = explode(':', $info); @endphp
                                                                            <tr>
                                                                                <td class="p-0 text-muted extra-small" style="width: 35%"><strong>{{ trim($partes[0] ?? '') }}:</strong></td>
                                                                                <td class="p-0 font-weight-bold text-dark extra-small">{{ trim($partes[1] ?? '') }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <span class="badge {{ $tipoSlug == 'componente-extra' ? 'badge-orange-soft' : 'badge-success-soft' }} text-dark font-weight-bold px-3 py-1">
                                                                    {!! $valor['despues'] ?? 'N/A' !!}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        <div class="alert bg-light border-0 mb-0 py-2">
                                            <p class="mb-0 small text-muted font-italic">
                                                <i class="fas fa-info-circle text-info mr-1"></i> {{ $detalles['mensaje'] ?? 'Sin descripción adicional' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="log-footer px-3 py-2 bg-gray-50 border-top d-flex justify-content-between">
                                    <small class="text-muted">ID Log: #{{ $log->id }}</small>
                                    <small class="text-muted">Operador: <span class="text-dark font-weight-bold">{{ $log->usuario->name ?? 'Sistema' }}</span></small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/5058/5058436.png" style="width: 80px; opacity: 0.2;">
                                <p class="text-muted mt-3">Sin actividad registrada en este activo</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="mt-4 pb-5">
        {{ $equipos->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
    /* Estilos de Marca y Colores */
    .bg-primary-soft { background-color: #e8f2ff !important; }
    .bg-purple-soft { background-color: #f3e8ff !important; }
    .text-purple { color: #6f42c1 !important; }
    .bg-orange { background-color: #fd7e14 !important; }
    .bg-gray-50 { background-color: #f9fafb !important; }
    
    .badge-success-soft { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-orange-soft { background-color: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-primary-soft { background-color: #e0e7ff; color: #4338ca; }

    /* Timeline Profesional */
    .timeline-v2 { position: relative; padding-left: 25px; border-left: 3px solid #e5e7eb; }
    .log-card { 
        position: relative; 
        border-radius: 10px; 
        border: 1px solid #edf2f7; 
        transition: transform 0.2s;
    }
    .log-card:hover { transform: translateX(5px); border-color: #cbd5e0; }
    .log-card::before {
        content: ''; position: absolute; left: -32px; top: 15px;
        width: 12px; height: 12px; background: #fff; border-radius: 50%; 
        border: 3px solid #007bff; z-index: 10;
    }

    /* Tipografía y Scroll */
    .extra-small { font-size: 0.78rem; }
    .text-decoration-line-through { text-decoration: line-through; opacity: 0.6; }
    .scroll-custom::-webkit-scrollbar { width: 5px; }
    .scroll-custom::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    .transition-icon { transition: 0.3s; }
    .collapsed .transition-icon { transform: rotate(-90deg); }
    .shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .rounded-lg { border-radius: 0.6rem; }
</style>
@endsection