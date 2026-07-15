@extends('adminlte::page')

@section('title', 'Inventario de Vehículos')

@section('css')
<style>
    :root {
        --fleet-primary: #17a2b8;
        --fleet-primary-dark: #117a8b;
        --fleet-soft: rgba(23, 162, 184, 0.08);
        --fleet-border: #e2e8f0;
        --fleet-text: #212529;
        --fleet-muted: #6c757d;
    }

    /* --- Layout principal --- */
    .inventory-wrapper {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        transition: all 0.35s ease;
    }

    .table-container {
        flex: 1;
        min-width: 0;
        transition: all 0.35s ease;
    }

    .preview-sidebar {
        width: 390px;
        position: sticky;
        top: 20px;
        display: none;
        opacity: 0;
        transform: translateX(28px);
        transition: opacity 0.35s ease, transform 0.35s ease;
    }

    .preview-sidebar.active {
        display: block;
        opacity: 1;
        transform: translateX(0);
    }

    /* --- Header operativo --- */
    .ops-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        background: #f8fafc;
        border: 1px solid var(--fleet-border);
        color: #475569;
    }

    .kpi-card {
        border: 0;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        padding: 12px 14px;
        min-width: 120px;
    }

    .kpi-label {
        display: block;
        font-size: 0.58rem;
        line-height: 1;
        color: var(--fleet-muted);
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
    }

    .kpi-value {
        display: block;
        font-size: 1.15rem;
        color: var(--fleet-text);
        font-weight: 900;
        line-height: 1.1;
        margin-top: 4px;
    }

    /* --- Panel de búsqueda --- */
    .search-panel-container {
        border-radius: 12px;
        overflow: hidden;
    }

    .search-header {
        background: linear-gradient(90deg, rgba(23, 162, 184, 0.08), rgba(255,255,255,1));
    }

    /* --- Tabla premium --- */
    .table-assets thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #f8f9fa;
        color: var(--fleet-primary);
        font-weight: 800;
        font-size: 0.72rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        padding: 14px 10px;
        white-space: nowrap;
    }

    .table-assets tbody tr {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .table-assets tbody tr:hover {
        background-color: rgba(23, 162, 184, 0.05) !important;
        transform: translateY(-1px);
        box-shadow: inset 4px 0 0 var(--fleet-primary);
    }

    .table-assets tbody tr.selected-row {
        background-color: rgba(23, 162, 184, 0.09) !important;
        box-shadow: inset 4px 0 0 var(--fleet-primary-dark);
    }

    .table-assets td {
        vertical-align: middle !important;
        padding: 12px 10px !important;
    }

    .asset-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--fleet-text);
    }

    .secondary-data {
        display: block;
        font-size: 0.78rem;
        color: var(--fleet-muted);
        margin-top: 3px;
    }

    .secondary-data i {
        width: 14px;
        text-align: center;
        color: var(--fleet-primary);
    }

    .row-inactive {
        background-color: #f8f9fa;
        opacity: 0.72;
    }

    .row-inactive:hover {
        box-shadow: inset 4px 0 0 #6c757d;
    }

    /* --- Badges operativos --- */
    .badge-status {
        font-size: 0.70rem;
        font-weight: 800;
        letter-spacing: 0.35px;
        padding: 6px 11px;
        border-radius: 4px;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        display: inline-block;
        min-width: 90px;
    }

    .badge-status-rojo {
        background-color: #fde8e8;
        color: #e02424;
        border: 1px solid #f8b4b4;
    }

    .badge-status-amarillo {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-status-verde {
        background-color: #def7ec;
        color: #03543f;
        border: 1px solid #bcf0da;
    }

    /* --- Acciones --- */
    .btn-group-actions .btn {
        border: 1px solid var(--fleet-border);
        background: #ffffff;
        color: #4a5568;
        transition: all 0.15s ease;
    }

    .btn-group-actions .btn:hover {
        background: #f7fafc;
        transform: translateY(-1px);
    }

    .btn-group-actions .btn-edit:hover { color: #ffc107; border-color: #ffc107; }
    .btn-group-actions .btn-view:hover { color: var(--fleet-primary); border-color: var(--fleet-primary); }
    .btn-group-actions .btn-delete:hover { color: #dc3545; border-color: #dc3545; }

    /* --- Sidebar / Command center --- */
    .asset-orb {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        background: rgba(23, 162, 184, 0.10);
        color: var(--fleet-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
    }

    .health-orb {
        --score: 0;
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: conic-gradient(var(--fleet-primary) calc(var(--score) * 1%), #e9ecef 0);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin: 0 auto;
    }

    .health-orb::after {
        content: '';
        position: absolute;
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #ffffff;
        box-shadow: inset 0 0 0 1px #eef2f7;
    }

    .health-orb-value {
        position: relative;
        z-index: 1;
        font-size: 1.1rem;
        font-weight: 900;
        color: var(--fleet-text);
    }

    .data-panel {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 10px;
        padding: 10px;
        font-size: 0.82rem;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 5px;
    }

    .data-row:last-child {
        margin-bottom: 0;
    }

    .data-row span:first-child {
        color: var(--fleet-muted);
    }

    .data-row strong,
    .data-row span:last-child {
        text-align: right;
        font-weight: 800;
        color: var(--fleet-text);
    }

    .metric-progress {
        height: 6px;
        border-radius: 3px;
        background-color: #e9ecef;
        margin-top: 5px;
        overflow: hidden;
    }

    .metric-progress-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.45s ease-in-out;
    }

    .indicator-card {
        padding: 10px;
        border: 1px solid #edf2f7;
        border-radius: 10px;
        background: #ffffff;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.035);
    }

    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    @media (max-width: 991px) {
        .inventory-wrapper {
            display: block;
        }

        .preview-sidebar {
            width: 100%;
            position: relative;
            top: 0;
            margin-top: 20px;
        }
    }
</style>
@stop

@section('content_header')
@php
    $vehiculosCollection = method_exists($vehiculos, 'getCollection') ? $vehiculos->getCollection() : collect($vehiculos);
    $totalVehiculos = method_exists($vehiculos, 'total') ? $vehiculos->total() : $vehiculosCollection->count();
    $activosPagina = $vehiculosCollection->where('is_active', true)->count();
    $criticosPagina = $vehiculosCollection->filter(fn($item) => $item->estatus_mantenimiento === 'rojo')->count();
    $empresaContexto = App\Models\Empresa::find(session('empresa_id'));
    $rolUsuario = strtoupper(auth()->user()->rol ?? auth()->user()->role ?? 'USUARIO');
@endphp

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <div class="mb-2 mb-md-0">
        <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
            Inventario de Vehículos
        </h1>
        <div class="mt-1">
            <span class="ops-chip">
                <i class="fas fa-user-shield mr-1"></i> Rol: {{ $rolUsuario }}
            </span>
            <span class="ops-chip ml-1">
                <i class="fas fa-database mr-1"></i> Flota filtrada por sucursal
            </span>
        </div>
    </div>

    <div class="d-flex align-items-center flex-wrap justify-content-end">
        <a href="{{ route('equipos.index') }}"
           class="btn btn-sm btn-outline-info font-weight-bold mr-1 shadow-sm mb-1"
           title="Ver Inventario de Equipos">
            <i class="fas fa-boxes mr-1"></i> Equipos
        </a>

        <a href="{{ route('vehiculos.index', array_merge(request()->except('page'), ['estatus' => 0])) }}"
           class="btn btn-sm btn-outline-danger font-weight-bold mr-1 shadow-sm mb-1"
           title="Mostrar vehículos inactivos">
            <i class="fas fa-ban mr-1"></i> Inactivos
        </a>

        <button type="button"
                class="btn btn-sm btn-outline-success font-weight-bold mr-1 shadow-sm mb-1"
                onclick="window.print()"
                title="Generar vista imprimible del inventario actual">
            <i class="fas fa-print mr-1"></i> Reporte
        </button>

        <button type="button"
                class="btn btn-sm btn-primary font-weight-bold shadow-sm px-3 mb-1"
                data-toggle="modal"
                data-target="#modalCrearVehiculo">
            <i class="fas fa-plus mr-1"></i> Nuevo Vehículo
        </button>
    </div>
</div>
@stop

@section('content')

@if(session('success'))
    <div class="callout callout-success alert alert-dismissible fade show shadow-sm border-0 mb-3" role="alert"
         style="border-left: 5px solid #28a745 !important; background-color: #ffffff;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="opacity: 0.5; outline: none;">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="d-flex align-items-center">
            <div class="text-success mr-3">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div>
                <h5 class="text-success font-weight-bold mb-0" style="font-size: 1.05rem;">
                    Operación completada
                </h5>
                <p class="mb-0 text-muted small">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

{{-- Contexto y KPIs --}}
<div class="d-flex border-0 shadow-sm p-3 bg-white mb-3 align-items-center justify-content-between flex-wrap" style="border-radius: 12px;">
    <div class="d-flex align-items-center mb-2 mb-md-0">
        <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center mr-3 shadow-sm"
             style="width: 42px; height: 42px;">
            <i class="fas fa-building"></i>
        </div>
        <div>
            <span class="text-muted d-block text-xs font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">
                Filtro Operativo de Contexto
            </span>
            <strong class="text-uppercase text-dark" style="font-size: 1.05rem;">
                {{ $empresaContexto->nombre ?? 'Sin Empresa Asignada' }}
            </strong>
        </div>
    </div>

    <div class="d-flex align-items-center flex-wrap justify-content-end">
        <div class="kpi-card mr-2 mb-1">
            <span class="kpi-label">Total</span>
            <span class="kpi-value">{{ $totalVehiculos }}</span>
        </div>
        <div class="kpi-card mr-2 mb-1">
            <span class="kpi-label">Activos</span>
            <span class="kpi-value text-success">{{ $activosPagina }}</span>
        </div>
        <div class="kpi-card mr-2 mb-1">
            <span class="kpi-label">Críticos</span>
            <span class="kpi-value text-danger">{{ $criticosPagina }}</span>
        </div>
        <a href="{{ route('vehiculos.cambiar_empresa') }}"
           class="btn btn-outline-secondary btn-sm px-3 font-weight-bold shadow-sm mb-1"
           style="border-radius: 8px;">
            <i class="fas fa-exchange-alt mr-1"></i> Cambiar Sucursal
        </a>
    </div>
</div>

{{-- Panel de búsqueda avanzada --}}
<div class="card card-outline card-info shadow-sm mb-3 search-panel-container {{ request()->anyFilled(['tipo_vehiculo_id', 'marca_id', 'ubicacion_id', 'estatus', 'buscar']) ? '' : 'collapsed-card' }}">
    <div class="card-header p-2 d-flex align-items-center search-header" data-card-widget="collapse" style="cursor: pointer;">
        <h3 class="card-title text-info font-weight-bold small mb-0 ml-2" style="letter-spacing: 0.5px;">
            <i class="fas fa-search mr-1"></i> PANEL DE BÚSQUEDA AVANZADA
        </h3>
        <div class="card-tools ml-auto mr-1">
            <button type="button" class="btn btn-tool text-info p-1" data-card-widget="collapse">
                <i class="fas {{ request()->anyFilled(['tipo_vehiculo_id', 'marca_id', 'ubicacion_id', 'estatus', 'buscar']) ? 'fa-minus' : 'fa-plus' }} transition-icon"></i>
            </button>
        </div>
    </div>

    <div class="card-body small" style="{{ request()->anyFilled(['tipo_vehiculo_id', 'marca_id', 'ubicacion_id', 'estatus', 'buscar']) ? 'display: block;' : 'display: none;' }} background-color: rgba(255, 255, 255, 0.5);">
        <form action="{{ route('vehiculos.index') }}" method="GET" autocomplete="off">
            <div class="row m-0 p-1">
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold text-muted">Búsqueda libre</label>
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           class="form-control form-control-sm"
                           placeholder="Placas, modelo o serie">
                </div>

                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted">Tipo</label>
                    <select name="tipo_vehiculo_id" class="form-control form-control-sm">
                        <option value="">-- Todos --</option>
                        @foreach($tiposVehiculo as $tipo)
                            <option value="{{ $tipo->id }}" {{ request('tipo_vehiculo_id') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted">Marca</label>
                    <select name="marca_id" class="form-control form-control-sm">
                        <option value="">-- Todas --</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ request('marca_id') == $marca->id ? 'selected' : '' }}>
                                {{ $marca->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted">Ubicación base</label>
                    <select name="ubicacion_id" class="form-control form-control-sm">
                        <option value="">-- Todas --</option>
                        @foreach($ubicaciones as $ub)
                            <option value="{{ $ub->id }}" {{ request('ubicacion_id') == $ub->id ? 'selected' : '' }}>
                                {{ $ub->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted">Estatus</label>
                    <select name="estatus" class="form-control form-control-sm">
                        <option value="">-- Todos --</option>
                        <option value="1" {{ request('estatus') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('estatus') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>

                <div class="col-md-1 mb-2 d-flex align-items-end justify-content-between">
                    <button type="submit" class="btn btn-sm btn-info w-50 mr-1" title="Filtrar">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-sm btn-secondary w-50" title="Limpiar filtros">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="inventory-wrapper">
    {{-- Tabla principal --}}
    <div class="card card-outline card-info shadow-sm table-container">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 620px; overflow-y: auto;">
                <table class="table table-hover table-assets mb-0" id="tablaVehiculos">
                    <thead>
                        <tr>
                            <th style="width: 60px" class="text-center">ID</th>
                            <th>Vehículo / Identificación</th>
                            <th>Asignación</th>
                            <th class="text-center" style="width: 155px;">Condición</th>
                            <th class="text-center" style="width: 135px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehiculos as $vehiculo)
                            @php
                                $estatus = $vehiculo->estatus_mantenimiento;
                                $estatusLabel = $estatus === 'rojo' ? 'Crítico' : ($estatus === 'amarillo' ? 'Próximo' : 'Al Día');
                                $estatusIcon = $estatus === 'rojo' ? 'fa-exclamation-triangle' : ($estatus === 'amarillo' ? 'fa-clock' : 'fa-check');
                                $estatusBadge = $estatus === 'rojo' ? 'badge-status-rojo' : ($estatus === 'amarillo' ? 'badge-status-amarillo' : 'badge-status-verde');
                                $doc = $vehiculo->documentacion ?? null;
                                $vigenciaSeguro = ($doc && $doc->vigencia_seguro) ? \Carbon\Carbon::parse($doc->vigencia_seguro)->format('d/m/Y') : 'N/D';
                            @endphp

                            <tr class="{{ !$vehiculo->is_active ? 'row-inactive' : '' }}"
                                data-id="{{ $vehiculo->id }}"
                                data-tipo="{{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }}"
                                data-marca="{{ $vehiculo->marca->nombre ?? 'N/A' }}"
                                data-modelo="{{ $vehiculo->modelo }}"
                                data-anio="{{ $vehiculo->anio ?? 'N/D' }}"
                                data-placas="{{ $vehiculo->placas ?? 'S/P' }}"
                                data-serie="{{ $vehiculo->no_serie ?? 'N/D' }}"
                                data-motor="{{ $vehiculo->no_motor ?? 'N/D' }}"
                                data-combustible="{{ $vehiculo->tipo_combustible ?? 'N/D' }}"
                                data-usuario="{{ $vehiculo->usuario->name ?? 'Sin asignar' }}"
                                data-email="{{ $vehiculo->usuario->email ?? 'N/D' }}"
                                data-ubicacion="{{ $vehiculo->ubicacion->nombre ?? 'N/D' }}"
                                data-activo="{{ $vehiculo->is_active ? '1' : '0' }}"
                                data-estatus="{{ $estatus }}"
                                data-estatus-label="{{ $estatusLabel }}"
                                data-doc-poliza="{{ $doc->no_poliza_seguro ?? 'N/D' }}"
                                data-doc-vigencia="{{ $vigenciaSeguro }}"
                                data-doc-tarjeta="{{ $doc->tarjeta_circulacion ?? 'N/D' }}"
                                data-indicadores='@json($vehiculo->indicadores_operativos ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)'>
                                <td class="text-center font-weight-bold text-muted" style="font-size: 0.85rem;">
                                    #{{ $vehiculo->id }}
                                </td>

                                <td>
                                    @if(session('actualizado_id') == $vehiculo->id)
                                        <span class="badge badge-warning mb-1" style="font-size: 0.65rem;">Actualizado</span>
                                    @endif

                                    <div class="asset-title">
                                        {{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }}
                                        <span class="text-info font-weight-normal">{{ $vehiculo->marca->nombre ?? 'N/A' }}</span>
                                    </div>
                                    <span class="secondary-data">
                                        <i class="fas fa-layer-group"></i> Mod: <strong>{{ $vehiculo->modelo }}</strong>
                                        <span class="mx-1 text-muted">|</span>
                                        <i class="fas fa-credit-card"></i> Placas: <strong class="text-secondary">{{ $vehiculo->placas ?? 'S/P' }}</strong>
                                    </span>
                                    <span class="secondary-data">
                                        <i class="fas fa-fingerprint"></i> Serie: <strong>{{ $vehiculo->no_serie ?? 'N/D' }}</strong>
                                    </span>
                                </td>

                                <td>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                        {{ $vehiculo->usuario->name ?? 'Sin asignar' }}
                                    </div>
                                    <span class="secondary-data">
                                        <i class="fas fa-map-marker-alt"></i> {{ $vehiculo->ubicacion->nombre ?? 'Sin ubicación' }}
                                    </span>
                                    @if($vehiculo->usuario)
                                        <span class="secondary-data">
                                            <i class="fas fa-envelope"></i> {{ $vehiculo->usuario->email }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning px-2 py-0.5 mt-1" style="font-size: 0.65rem; font-weight: 800; color: #333;">
                                            Por asignar
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge-status {{ $estatusBadge }}">
                                        <i class="fas {{ $estatusIcon }} mr-1"></i> {{ $estatusLabel }}
                                    </span>
                                    <span class="secondary-data mt-1">
                                        {{ $vehiculo->is_active ? 'Operativo' : 'Inactivo' }}
                                    </span>
                                </td>

                                <td class="text-center" onclick="event.stopPropagation();">
                                    <div class="btn-group btn-group-actions shadow-sm">
                                        <a href="{{ route('vehiculos.edit', $vehiculo) }}"
                                           class="btn btn-sm btn-edit"
                                           title="Editar parámetros"
                                           onclick="event.stopPropagation();">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <a href="{{ route('vehiculos.show', $vehiculo) }}"
                                           class="btn btn-sm btn-view"
                                           title="Ver ficha técnica"
                                           onclick="event.stopPropagation();">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('vehiculos.destroy', $vehiculo) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="event.stopPropagation(); return confirm('¿Seguro que deseas dar de baja este vehículo del sistema?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-delete" title="Inactivar / eliminar activo">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-gray-200"></i>
                                        <p class="h6 font-weight-bold text-secondary mb-1">
                                            No se encontraron vehículos registrados
                                        </p>
                                        <small>Ajusta los filtros o registra un nuevo vehículo.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 d-flex align-items-center justify-content-between py-2">
            <div>
                @if(method_exists($vehiculos, 'links'))
                    {{ $vehiculos->links() }}
                @endif
            </div>

            <div class="d-flex align-items-center ml-auto" style="opacity: 0.85;">
                <div class="mx-2 d-none d-md-block" style="border-left: 1px solid #e2e8f0; height: 35px;"></div>
                <div class="text-right mr-2 d-none d-lg-block">
                    <small class="text-muted d-block" style="font-size: 0.55rem; line-height: 1; letter-spacing: 0.8px; font-weight: 800;">
                        SISTEMA DE GESTIÓN
                    </small>
                    <span class="font-weight-bold text-dark" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                        ACTIVOS TI
                    </span>
                </div>
                <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}"
                     alt="Logo PIHCSA"
                     style="height: 32px; width: auto; filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.08));">
            </div>
        </div>
    </div>

    {{-- Panel lateral pro --}}
    <div class="card card-outline card-info shadow-sm preview-sidebar" id="sidebarInspeccion">
        <div class="card-header p-3 d-flex align-items-center bg-light">
            <h3 class="card-title text-info font-weight-bold text-sm mb-0">
                <i class="fas fa-satellite-dish mr-1 text-xs"></i> COMMAND CENTER DEL ACTIVO
            </h3>
            <button type="button" class="close ml-auto" id="closeSidebar" style="outline:none; font-size: 1.2rem;">
                &times;
            </button>
        </div>

        <div class="card-body p-3">
            <div class="text-center pb-3 border-bottom mb-3">
                <div class="asset-orb mb-2">
                    <i class="fas fa-car fa-2x"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1" id="sideVehiculo">Selecciona un vehículo</h5>
                <span class="badge badge-info px-2 py-1 font-weight-bold text-xs shadow-sm" id="sidePlacas">
                    Placas: ---
                </span>
                <span class="badge badge-secondary px-2 py-1 font-weight-bold text-xs shadow-sm" id="sideEstado">
                    Estado: ---
                </span>
            </div>

            <div class="row align-items-center mb-3">
                <div class="col-5 text-center">
                    <div class="health-orb" id="sideHealthOrb" style="--score: 0;">
                        <span class="health-orb-value" id="sideHealthScore">0%</span>
                    </div>
                </div>
                <div class="col-7">
                    <span class="text-xs text-muted font-weight-bold text-uppercase d-block">
                        Índice de condición
                    </span>
                    <strong class="text-dark d-block" id="sideHealthLabel">Sin diagnóstico</strong>
                    <small class="text-muted" id="sideHealthDetail">
                        El índice se deriva de los indicadores reales del modelo.
                    </small>
                </div>
            </div>

            <div class="mb-3">
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">
                    Asignación operativa
                </span>
                <div class="data-panel">
                    <div class="data-row">
                        <span>Resguardante:</span>
                        <strong id="sideUser">---</strong>
                    </div>
                    <div class="data-row">
                        <span>Contacto:</span>
                        <span class="text-truncate" id="sideEmail" style="max-width: 190px;">---</span>
                    </div>
                    <div class="data-row">
                        <span>Ubicación:</span>
                        <strong id="sideUbicacion">---</strong>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">
                    Identificación técnica
                </span>
                <div class="data-panel">
                    <div class="data-row">
                        <span>Modelo:</span>
                        <strong id="sideModelo">---</strong>
                    </div>
                    <div class="data-row">
                        <span>Año:</span>
                        <strong id="sideAnio">---</strong>
                    </div>
                    <div class="data-row">
                        <span>Serie:</span>
                        <strong class="text-truncate" id="sideSerie" style="max-width: 190px;">---</strong>
                    </div>
                    <div class="data-row">
                        <span>Motor:</span>
                        <strong class="text-truncate" id="sideMotor" style="max-width: 190px;">---</strong>
                    </div>
                    <div class="data-row">
                        <span>Combustible:</span>
                        <strong id="sideCombustible">---</strong>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">
                    Matriz de condición operativa
                </span>
                <div id="sideIndicadores">
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p class="small mb-0">Selecciona un vehículo para calcular indicadores.</p>
                    </div>
                </div>
            </div>

            <div>
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">
                    Documentación crítica
                </span>
                <div class="p-2 border rounded bg-dark shadow-sm">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-shield-alt text-info mr-2"></i>
                        <span class="text-xs text-monospace" style="color: #a3e635;">
                            Póliza: <strong id="sidePoliza">---</strong>
                        </span>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-calendar-check text-warning mr-2"></i>
                        <span class="text-xs text-monospace" style="color: #fde68a;">
                            Vigencia seguro: <strong id="sideVigencia">---</strong>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-id-card text-secondary mr-2"></i>
                        <span class="text-xs text-monospace" style="color: #cbd5e1;">
                            Tarjeta: <strong id="sideTarjeta">---</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('vehiculos.modal_crear')

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let catalogosCargados = false;
    let dataCatalogos = null;

    const sidebar = document.getElementById('sidebarInspeccion');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const table = document.getElementById('tablaVehiculos');
    const rows = document.querySelectorAll('#tablaVehiculos tbody tr');

    rows.forEach((row, index) => {
        if (row.classList.contains('empty-row')) return;

        row.style.opacity = '0';
        row.style.transform = 'translateY(5px)';

        setTimeout(() => {
            row.style.transition = 'all 0.25s ease-in-out';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 28);
    });

    rows.forEach(row => {
        row.addEventListener('click', function () {
            if (this.classList.contains('empty-row') || this.cells.length <= 1) return;

            rows.forEach(r => r.classList.remove('selected-row'));
            this.classList.add('selected-row');

            const dataset = this.dataset;
            const indicadores = parseIndicadores(dataset.indicadores);
            const scoreGlobal = calcularScoreGlobal(indicadores);

            setText('sideVehiculo', `${dataset.tipo || 'Vehículo'} ${dataset.marca || 'N/A'}`);
            setText('sidePlacas', `Placas: ${dataset.placas || 'S/P'}`);
            setText('sideUser', dataset.usuario || 'Sin asignar');
            setText('sideEmail', dataset.email || 'N/D');
            setText('sideUbicacion', dataset.ubicacion || 'N/D');
            setText('sideModelo', dataset.modelo || 'N/D');
            setText('sideAnio', dataset.anio || 'N/D');
            setText('sideSerie', dataset.serie || 'N/D');
            setText('sideMotor', dataset.motor || 'N/D');
            setText('sideCombustible', dataset.combustible || 'N/D');
            setText('sidePoliza', dataset.docPoliza || 'N/D');
            setText('sideVigencia', dataset.docVigencia || 'N/D');
            setText('sideTarjeta', dataset.docTarjeta || 'N/D');

            renderEstado(dataset.activo, dataset.estatusLabel);
            renderHealth(scoreGlobal, indicadores.length);
            renderIndicadores(indicadores);

            sidebar.classList.add('active');
        });
    });

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', function () {
            sidebar.classList.remove('active');
            rows.forEach(r => r.classList.remove('selected-row'));
        });
    }

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.innerText = value;
    }

    function parseIndicadores(raw) {
        try {
            const parsed = JSON.parse(raw || '[]');
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            console.warn('Indicadores inválidos:', error);
            return [];
        }
    }

    function calcularScoreGlobal(indicadores) {
        const scores = indicadores
            .map(item => Number(item.score))
            .filter(score => !Number.isNaN(score));

        if (!scores.length) return 0;

        const total = scores.reduce((sum, score) => sum + score, 0);
        return Math.round(total / scores.length);
    }

    function renderEstado(activo, estatusLabel) {
        const el = document.getElementById('sideEstado');
        if (!el) return;

        el.className = 'badge px-2 py-1 font-weight-bold text-xs shadow-sm ' + (activo === '1' ? 'badge-success' : 'badge-danger');
        el.innerText = activo === '1' ? `Operativo · ${estatusLabel || 'N/D'}` : `Inactivo · ${estatusLabel || 'N/D'}`;
    }

    function renderHealth(score, totalIndicadores) {
        const orb = document.getElementById('sideHealthOrb');
        const scoreText = document.getElementById('sideHealthScore');
        const label = document.getElementById('sideHealthLabel');
        const detail = document.getElementById('sideHealthDetail');

        if (orb) orb.style.setProperty('--score', score);
        if (scoreText) scoreText.innerText = `${score}%`;

        if (!totalIndicadores) {
            if (label) label.innerText = 'Sin diagnóstico';
            if (detail) detail.innerText = 'No hay indicadores suficientes para calcular condición.';
            return;
        }

        if (score <= 35) {
            if (label) label.innerText = 'Riesgo operativo alto';
            if (detail) detail.innerText = 'Requiere revisión documental u operativa prioritaria.';
        } else if (score <= 70) {
            if (label) label.innerText = 'Condición preventiva';
            if (detail) detail.innerText = 'Existen vencimientos próximos o datos por completar.';
        } else {
            if (label) label.innerText = 'Condición estable';
            if (detail) detail.innerText = 'El activo mantiene parámetros operativos saludables.';
        }
    }

    function renderIndicadores(indicadores) {
        const container = document.getElementById('sideIndicadores');
        if (!container) return;

        if (!indicadores.length) {
            container.innerHTML = `
                <div class="text-center text-muted py-3">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <p class="small mb-0">No hay indicadores disponibles para este activo.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = indicadores.map(indicador => {
            const icon = sanitizeClass(indicador.icon || 'fa-chart-line');
            const badge = sanitizeClass(indicador.badge || 'badge-secondary');
            const barClass = sanitizeClass(indicador.class || 'bg-secondary');
            const score = clamp(Number(indicador.score || 0), 0, 100);

            return `
                <div class="indicator-card">
                    <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                        <span>
                            <i class="fas ${icon} mr-1 text-muted"></i>
                            ${escapeHtml(indicador.label || 'Indicador')}
                        </span>
                        <span class="badge ${badge} px-2 py-1">
                            ${escapeHtml(indicador.status || 'N/D')}
                        </span>
                    </div>

                    <div class="metric-progress">
                        <div class="metric-progress-bar ${barClass}" style="width: ${score}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">${escapeHtml(indicador.detail || 'Sin detalle')}</small>
                        <small class="font-weight-bold text-dark">${score}%</small>
                    </div>
                </div>
            `;
        }).join('');
    }

    function clamp(value, min, max) {
        return Math.max(min, Math.min(max, value));
    }

    function sanitizeClass(value) {
        return String(value || '').replace(/[^a-zA-Z0-9_\-\s]/g, '');
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function cargarOpciones(selectId, items, propNombre, selectedId = null) {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.innerHTML = '<option value="" disabled selected>Selecciona una opción...</option>';

        (items || []).forEach(item => {
            const isSelected = selectedId && item.id == selectedId ? 'selected' : '';
            select.innerHTML += `<option value="${item.id}" ${isSelected}>${escapeHtml(item[propNombre])}</option>`;
        });
    }

    function prefetchCatalogos(callback) {
        if (catalogosCargados) {
            if (callback) callback();
            return;
        }

        fetch("{{ route('vehiculos.filtros') }}")
            .then(response => response.json())
            .then(data => {
                dataCatalogos = data;
                catalogosCargados = true;
                if (callback) callback();
            })
            .catch(error => console.error('Error al precargar catálogos:', error));
    }

    if (window.jQuery && document.getElementById('modalCrearVehiculo')) {
        $('#modalCrearVehiculo').on('show.bs.modal', function () {
            prefetchCatalogos(() => {
                cargarOpciones('tipo_vehiculo_id', dataCatalogos.tipos, 'nombre');
                cargarOpciones('marca_id', dataCatalogos.marcas, 'nombre');
                cargarOpciones('usuario_id', dataCatalogos.usuarios || [], 'name');
                cargarOpciones('ubicacion_id', dataCatalogos.ubicaciones || [], 'nombre');
            });
        });
    }
});
</script>
@stop
