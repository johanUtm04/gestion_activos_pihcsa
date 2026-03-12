@extends('adminlte::page')

@section('title', 'Ficha Técnica | ' . $equipo->serial)

@section('css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e5799 0%, #2989d8 50%, #207cca 100%);
        --success-soft: #e8f5e9;
        --info-soft: #e3f2fd;
        --border-radius-lg: 12px;
    }

    .info-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
        background: #fff;
    }

    .info-card:hover { transform: translateY(-2px); }

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

    .component-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        border-left: 4px solid #28a745;
    }

    .factura-badge {
        background-color: var(--info-soft);
        color: #007bff;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #b3e5fc;
        font-weight: bold;
    }

    @media print {
        .no-print { display: none !important; }
        .main-sidebar, .main-header, .main-footer { display: none !important; }
        .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: white !important; }
        .info-card { box-shadow: none !important; border: 1px solid #eee !important; }
        body { background: white !important; font-size: 12pt; }
        .section-title { border-bottom: 2px solid #333 !important; }
        .component-item { border: 1px solid #ddd !important; border-left: 5px solid #333 !important; }
        .print-signature { display: block !important; margin-top: 80px; }
    }

    .print-signature { display: none; }
</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center no-print">
    <div>
        <h1 class="m-0 text-dark font-weight-bold">Ficha Técnica Digital</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('equipos.index') }}">Equipos</a></li>
            <li class="breadcrumb-item active">Ficha #{{ $equipo->serial }}</li>
        </ol>
    </div>
    <div>
        <button onclick="window.print();" class="btn btn-dark btn-lg shadow-sm">
            <i class="fas fa-print mr-2"></i> Generar Documento
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    
    {{-- BLOQUE 1: IDENTIFICACIÓN Y DATOS ECONÓMICOS --}}
    <div class="card info-card mb-4 border-top border-success" style="border-top-width: 4px !important;">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center border-right">
                    <div class="label-header">Código de Activo</div>
                    <div class="h3 font-weight-bold text-success mb-2">{{ $equipo->serial }}</div>
                    <div class="label-header">Factura Asociada</div>
                    @if($equipo->numero_factura)
                        <span class="factura-badge"><i class="fas fa-file-invoice-dollar mr-1"></i> {{ $equipo->numero_factura }}</span>
                    @else
                        <span class="badge badge-light text-muted border">Sin Factura</span>
                    @endif
                    <div class="label-header">Inicio de Uso</div>
                    <div class="h3 font-weight-bold text-success mb-2">{{ $equipo->fecha_inicio_uso ?? Pendiente }}</div>
                </div>
                
                <div class="col-md-9 pl-md-4">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="label-header">Tipo de Dispositivo</div>
                            <div class="value-text"><i class="fas fa-laptop mr-1"></i> {{ $equipo->tipoActivo?->nombre ?? 'N/A'}}</div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="label-header">Fabricante / Modelo</div>
                            <div class="value-text">
                                <span class="font-weight-bold text-dark">{{ $equipo->marca?->nombre ?? 'Genérico' }}</span>
                                <span class="text-muted">| {{ $equipo->modelo ?? 'S/M' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 mb-1">
                            <div class="label-header">Fecha Adquisición</div>
                            <div class="value-text text-muted">{{ $equipo->fecha_adquisicion ?? 'No registrada' }}</div>
                        </div>
                        <div class="col-sm-2 mb-1">
                            <div class="label-header">Valor Inicial</div>
                            <div class="value-text text-muted">$ {{ $equipo->valor_inicial ?? 'No registrado' }} mxn.</div>
                        </div>
                    </div>
                    <div class="row border-top pt-3">
                        <div class="col-sm-4">
                            <div class="label-header">Usuario Responsable</div>
                            <div class="value-text text-primary">
                                <i class="fas fa-user-circle mr-1"></i> {{ $equipo->usuario->name ?? 'Disponible' }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="label-header">Departamento / Ubicación</div>
                            <span class="font-weight-bold text-dark"> {{ $equipo->departamento_perteneciente ?? 'N/A' }}</span>
                            <span class="text-muted">| {{ $equipo->ubicacion->nombre ?? 'N/A' }}</span>
                                
                        </div>
                        <div class="col-sm-4">
                            <div class="label-header">Sistema Operativo</div>
                            <div class="value-text text-info">
                                <i class="fab fa-windows mr-1"></i> {{ $equipo->sistema_operativo ?? 'No especificado' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- BLOQUE 2: HARDWARE --}}
        <div class="col-lg-7">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="section-title"><i class="fas fa-microchip"></i> Arquitectura de Hardware</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small font-weight-bold">PROCESADORES</label>
                            @forelse($equipo->procesadores->where('is_active', 1) as $cpu)
                                <div class="component-item">
                                    <div class="font-weight-bold">{{ $cpu->marca }}</div>
                                    <div class="text-sm"> <strong>Tipo</strong> {{ $cpu->descripcion_tipo }}</div>
                                    <div class="text-sm">{{ $cpu->clock_ghz }} <strong>Ghz</strong></div>
                                </div>
                            @empty
                                <div class="text-muted p-2 italic">Sin CPUs registrados</div>
                            @endforelse
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small font-weight-bold">MEMORIA RAM</label>
                            @forelse($equipo->rams->where('is_active', 1) as $ram)
                                <div class="component-item" style="border-left-color: #007bff;">
                                    <div class="font-weight-bold">{{ $ram->capacidad_gb }} GB</div>
                                    <div class="text-sm">{{ $ram->tipo_chz }} @ {{ $ram->clock_mhz }} MHz <strong>Serial:</strong>{{ $ram->serial }} </div>
                                </div>
                            @empty
                                <div class="text-muted p-2 italic">Sin RAM registrada</div>
                            @endforelse
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-database"></i> Almacenamiento</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover border">
                            <thead class="bg-light">
                                <tr class="text-center text-xs uppercase font-weight-bold">
                                    <th>Capacidad</th>
                                    <th>Tecnología</th>
                                    <th>Interface</th>
                                    <th>Serial</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse($equipo->discosDuros->where('is_active', 1) as $disco)
                                    <tr>
                                        <td class="font-weight-bold">{{ $disco->capacidad }}</td>
                                        <td><span class="badge badge-secondary">{{ $disco->tipo_hdd_ssd }}</span></td>
                                        <td>{{ $disco->interface }}</td>
                                        <td>{{ $disco->serial }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted">Sin discos registrados</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOQUE 3: PERIFÉRICOS --}}
        <div class="col-lg-5">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="section-title"><i class="fas fa-desktop"></i> Pantallas</h5>
                    @forelse($equipo->monitores->where('is_active', 1) as $monitor)
                        <div class="p-2 mb-2 border rounded bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge badge-dark">{{ $monitor->marca }}</span>
                                <span class="font-weight-bold ml-1">{{ $monitor->escala_pulgadas }}"</span>
                                <div class="text-xs text-muted"><strong>Serial:</strong> {{ $monitor->serial }}</div>
                                <div class="text-xs text-muted"><strong>Interface:</strong> {{ $monitor->interface }}</div>
                            </div>
                            <i class="fas fa-tv text-secondary opacity-5"></i>
                        </div>
                    @empty
                        <div class="alert alert-light text-center border text-sm">Sin monitores</div>
                    @endforelse

                    <h5 class="section-title mt-4"><i class="fas fa-mouse"></i> Periféricos</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody class="text-sm">
                                @forelse($equipo->perifericos->where('is_active', 1) as $peri)
                                    <tr>
                                        <td class="font-weight-bold">{{ $peri->tipo }}</td>
                                        <td>{{ $peri->marca }}</td>
                                        <td class="text-right text-muted"><small>{{ $peri->serial }}</small></td>
                                        <td class="text-right text-muted"><small>{{ $peri->interface }}</small></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Sin periféricos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER LEGAL --}}
    <div class="row no-print">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Trazabilidad:</strong> Este activo está vinculado a la factura <strong>{{ $equipo->numero_factura ?? 'PENDIENTE' }}</strong> y tiene un valor inicial de <strong>${{ number_format($equipo->valor_inicial, 2) }}</strong>.
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE FIRMAS --}}
    <div class="print-signature container-fluid">
        <div class="row mt-5 pt-5 text-center">
            <div class="col-6">
                <div style="border-top: 2px solid #000; width: 80%; margin: 0 auto;"></div>
                <p class="mt-2 font-weight-bold">Responsable TI</p>
                <small>{{ Auth::user()->name }}</small>
            </div>
            <div class="col-6">
                <div style="border-top: 2px solid #000; width: 80%; margin: 0 auto;"></div>
                <p class="mt-2 font-weight-bold">Usuario Final</p>
                <small>{{ $equipo->usuario->name ?? '_______________________' }}</small>
            </div>
        </div>
    </div>
</div>
@stop