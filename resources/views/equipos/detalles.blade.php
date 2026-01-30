@extends('adminlte::page')

@section('title', 'Ficha Técnica | ' . $equipo->serial)

@section('css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e5799 0%, #2989d8 50%, #207cca 100%);
        --success-soft: #e8f5e9;
        --border-radius-lg: 12px;
    }

    .info-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
        background: #fff;
    }

    .info-card:hover {
        transform: translateY(-2px);
    }

    .label-header {
        color: #8392a5;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .value-text {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .section-title {
        display: flex;
        align-items: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f1f1;
    }

    .section-title i {
        margin-right: 10px;
        color: #28a745;
        background: var(--success-soft);
        padding: 8px;
        border-radius: 8px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .component-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        border-left: 4px solid #28a745;
    }

    @media print {
        .no-print { display: none !important; }
        .main-sidebar, .main-header, .main-footer { display: none !important; }
        .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: white !important; }
        
        .info-card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
        }
        
        body { background: white !important; font-size: 12pt; }
        .section-title { border-bottom: 2px solid #333 !important; }
        .component-item { border: 1px solid #ddd !important; border-left: 5px solid #333 !important; }
        
        .print-signature {
            display: block !important;
            margin-top: 80px;
        }
    }

    .print-signature { display: none; }
</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center no-print">
    <div>
        <h1 class="m-0 text-dark font-weight-bold">Ficha Técnica Digital</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Equipos</a></li>
            <li class="breadcrumb-item active">Ficha #{{ $equipo->serial }}</li>
        </ol>
    </div>
    <div>
        <!-- <button onclick="window.print();" class="btn btn-dark btn-lg shadow-sm">
            <i class="fas fa-print mr-2"></i> Generar Documento
        </button> -->
    <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver al inventario
    </a>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    
    {{-- BLOQUE 1: HEADER DE IDENTIFICACIÓN --}}
    <div class="card info-card mb-4 border-top border-success" style="border-top-width: 4px !important;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-auto text-center pr-md-5 border-right">
                    <div class="label-header">Código de Activo</div>
                    <div class="h3 font-weight-bold text-success mb-0">{{ $equipo->serial }}</div>
                    
                </div>
                
                <div class="col-md pl-md-5">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="label-header">Tipo de Dispositivo</div>
                            <div class="value-text"><i class="fas fa-laptop mr-1"></i> {{ $equipo->tipoActivo?->nombre ?? 'No disponible'}}</div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="label-header">Fabricante / Modelo</div>
                            <div class="value-text">{{ $equipo->marca?->nombre ?? 'No disponible'}}</div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="label-header">Fecha Asignación</div>
                            <div class="value-text text-muted">{{ $equipo->fecha_adquisicion ?? 'Sin Fecha' }}</div>
                        </div>
                    </div>
                    <div class="row border-top pt-3">
                        <div class="col-sm-6">
                            <div class="label-header">Usuario Responsable</div>
                            <div class="value-text text-primary">
                                <i class="fas fa-user-circle mr-1"></i> {{ $equipo->usuario->name ?? 'Disponible en Inventario' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="label-header">Departamento / Ubicación</div>
                            <div class="value-text"><i class="fas fa-map-marker-alt mr-1"></i> {{ $equipo->ubicacion->nombre ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BLOQUE 2: HARDWARE (CPU/RAM/DISCO) --}}
        <div class="col-lg-7">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="section-title"><i class="fas fa-microchip"></i> Especificaciones de Hardware</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small font-weight-bold">PROCESADORES</label>
                            @forelse($equipo->procesadores->where('is_active', 1) as $cpu)
                                <div class="component-item">
                                    <div class="font-weight-bold">{{ $cpu->marca }}</div>
                                    <div class="text-sm">{{ $cpu->descripcion_tipo }}</div>
                                </div>
                            @empty
                                <div class="text-muted italic">No hay CPUs registrados</div>
                            @endforelse
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small font-weight-bold">MEMORIA RAM</label>
                            @forelse($equipo->rams->where('is_active', 1) as $ram)
                                <div class="component-item" style="border-left-color: #007bff;">
                                    <div class="font-weight-bold">{{ $ram->capacidad_gb }} GB</div>
                                    <div class="text-sm">{{ $ram->tipo_chz }} @ {{ $ram->clock_mhz }} MHz</div>
                                </div>
                            @empty
                                <div class="text-muted italic">Sin memoria RAM registrada</div>
                            @endforelse
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-database"></i> Unidades de Almacenamiento</h5>
                    <div class="table-responsive">
                        <table class="table table-hover border">
                            <thead class="bg-light text-center text-xs uppercase font-weight-bold">
                                <tr>
                                    <th>Capacidad</th>
                                    <th>Tecnología</th>
                                    <th>Interface</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse($equipo->discosDuros->where('is_active', 1) as $disco)
                                    <tr>
                                        <td class="font-weight-bold">{{ $disco->capacidad }}</td>
                                        <td><span class="badge badge-secondary">{{ $disco->tipo_hdd_ssd }}</span></td>
                                        <td>{{ $disco->interface }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted text-center">Sin discos registrados</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: MONITORES Y PERIFÉRICOS --}}
        <div class="col-lg-5">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="section-title"><i class="fas fa-desktop"></i> Pantallas Asociadas</h5>
                    @forelse($equipo->monitores->where('is_active', 1) as $monitor)
                        <div class="p-3 mb-3 border rounded shadow-sm bg-light">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge badge-dark mb-1">{{ $monitor->marca }}</span>
                                    <div class="h5 mb-0 font-weight-bold">{{ $monitor->escala_pulgadas }}" Pulgadas</div>
                                    <small class="text-muted">Serial: {{ $monitor->serial }}</small>
                                </div>
                                <i class="fas fa-tv fa-2x text-light"></i>
                            </div>
                            <div class="mt-2 text-xs text-uppercase font-weight-bold text-success">
                                <i class="fas fa-plug mr-1"></i> {{ $monitor->interface }}
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light text-center border">Sin monitores registrados</div>
                    @endforelse

                    <h5 class="section-title mt-4"><i class="fas fa-mouse"></i> Periféricos</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <tbody class="text-sm">
                                @forelse($equipo->perifericos->where('is_active', 1) as $peri)
                                    <tr>
                                        <td class="font-weight-bold">{{ $peri->tipo }}</td>
                                        <td>{{ $peri->marca }}</td>
                                        <td class="text-right text-muted"><small>{{ $peri->serial }}</small></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No hay periféricos registrados</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER / NOTAS --}}
    <div class="card info-card mt-3 mb-5">
        <div class="card-body py-3 bg-light rounded shadow-inner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="text-muted small mb-0 italic">
                        <i class="fas fa-info-circle mr-1"></i>
                        Este documento es una representación técnica oficial del activo <strong>#{{ $equipo->serial }}</strong>. 
                        Cualquier modificación de hardware debe ser reportada al departamento de TI.
                        Generado el {{ date('d/m/Y H:i') }}.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE FIRMAS (SOLO IMPRESIÓN) --}}
    <div class="print-signature container-fluid">
        <div class="row mt-5 pt-5 text-center">
            <div class="col-6">
                <div style="border-top: 2px solid #000; width: 80%; margin: 0 auto;"></div>
                <p class="mt-2 font-weight-bold">Firma de Responsable TI</p>
            </div>
            <div class="col-6">
                <div style="border-top: 2px solid #000; width: 80%; margin: 0 auto;"></div>
                <p class="mt-2 font-weight-bold">Firma de Recibido (Usuario)</p>
            </div>
        </div>
    </div>
</div>
@stop