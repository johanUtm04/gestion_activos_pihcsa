@extends('adminlte::page')

@section('title', 'Inventario de Activos TI')

@section('css')
<style>

    .table-assets thead th {
        background-color: #f4f6f9; 
        color: #17a2b8; 
        font-weight: 800;
        border-bottom: 3px solid #17a2b8;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .table-assets tbody td {
        vertical-align: middle; 
        font-size: 15px;
        transition: all 0.2s;
    }

    .equipo-row { cursor: pointer; }
    .equipo-row:hover { background-color: rgba(23, 162, 184, 0.05) !important; }

    .secondary-data {
        color: #6c757d; 
        font-size: 0.85em;
        display: block; 
    }

    .sticky-details {
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
    }

    .detail-card {
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        border-radius: 12px;
        overflow: hidden;
    }

    .detail-header-premium {
        background: linear-gradient(45deg, #17a2b8, #117a8b);
        color: white;
        padding: 20px;
    }

    .info-box-custom {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-box-custom:last-child { border-bottom: none; }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-weight: 600;
        color: #333;
        text-align: right;
    }

    .section-divider {
        background: #f8f9fa;
        padding: 6px 15px;
        font-size: 0.7rem;
        font-weight: 800;
        color: #17a2b8;
        border-top: 1px solid #eee;
    }

    .empty-state-container {
        padding: 60px 20px;
        text-align: center;
        color: #ccc;
    }

    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-details { animation: fadeInRight 0.4s ease-out; }

    .custom-input {
    display: none;
    margin-top: 10px;
    }

</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
    <h1 class="text-dark font-weight-bold mb-1">
        <i class="fas fa-boxes text-info mr-2"></i>Inventario de Activos
    </h1>
    <p class="text-muted mb-2">
        Consulta, edición y seguimiento de equipos tecnológicos y periféricos
    </p>
    <div class="d-flex align-items-center">
        <small class="text-secondary mr-2">Actualmente eres:</small>
        <span class="badge badge-outline-info" style="border: 1px solid #17a2b8; color: #17a2b8; background: transparent;">
            <i class="fas fa-user-shield mr-1"></i>{{ ucfirst(auth()->user()->rol) }}
        </span>
    </div>
</div>

    @can('crear-equipo')
    <div class="d-flex" style="gap: 10px;">
        <a href="{{ route('equipos.reporte') }}" class="btn btn-outline-success shadow-sm d-flex align-items-center">
            <i class="fas fa-file-excel mr-2"></i> Reporte General
        </a>

        <a href="{{ route('equipos.wizard.create') }}" class="btn btn-info shadow-sm d-flex align-items-center">
            <i class="fas fa-plus-circle mr-2"></i> Nuevo Activo
        </a>
    </div>
    @endcan
</div>
@stop

@section('content')
    {{-- Alertas --}}
    @foreach (['success', 'danger', 'warning', 'info', 'secondary'] as $msg)
        @if(Session::has($msg))
            <div class="alert alert-{{ $msg }} alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-info-circle mr-2"></i> {{ Session::get($msg) }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
    @endforeach

    {{-- SECCIÓN DE FILTROS AVANZADOS --}}
    <div class="card card-outline card-info shadow-sm mb-4 collapsed-card">
        <div class="card-header">
            <h3 class="card-title text-info font-weight-bold">
                <i class="fas fa-filter mr-1"></i> Panel de Búsqueda
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('equipos.index') }}" method="GET">
                <div class="row">
                    {{-- 1. Buscador por Usuario --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Usuario</label>
                            <select name="usuario_id" class="form-control form-control-sm">
                                <option value="">-- Todos los usuarios --</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 2. Filtro por Ubicacion --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Ubicacion</label>
                            <select name="ubicacion_id" class="form-control form-control-sm">
                                <option value="">-- Todas las sedes --</option>
                                @foreach($ubicaciones as $u)
                                    <option value="{{ $u->id }}" {{ request('ubicacion_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filtro por Tipo de Activo --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Tipo de Activo</label>
                            <select name="tipo_activo_id" class="form-control form-control-sm">
                                <option value="">-- Todas los tipos --</option>
                                @foreach($tipos as $m)
                                    <option value="{{ $m->id }}" {{ request('tipo_activo_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filtro por Marca --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Marca</label>
                            <select name="marca_id" class="form-control form-control-sm">
                                <option value="">-- Todas las marcas --</option>
                                @foreach($marcas as $m)
                                    <option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 4. Botones --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-info btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Filtrar
                                </button>
                                <a href="{{ route('equipos.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar búsqueda">
                                    <i class="fas fa-sync-alt text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        {{-- TABLA (LADO IZQUIERDO) --}}
        <div class="col-xl-8">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-assets mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th>Activo / Serial</th>
                                    <th>Asignado a</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equipos as $equipo)
                                <tr id="equipo-{{ $equipo->id }}" class="equipo-row"
                                    data-id="{{ $equipo->id }}"
                                    data-marca="{{ $equipo->marca?->nombre ?? 'Genérica' }}"
                                    data-tipo="{{ $equipo->tipoActivo?->nombre ?? 'Generico' }}"
                                    data-serial="{{ $equipo->serial }}"
                                    data-so="{{ $equipo->sistema_operativo }}"
                                    data-usuario="{{ $equipo->usuario->name ?? 'Sin asignar' }}"
                                    data-email="{{ $equipo->usuario->email ?? '-' }}"
                                    data-ubicacion="{{ $equipo->ubicacion->nombre ?? 'Sin ubicación' }}"
                                    data-valor="{{ number_format($equipo->valor_inicial, 2) }}"
                                    data-fecha="{{ $equipo->fecha_adquisicion ?? 'Sin registro' }}"a
                                    data-vida="{{ $equipo->vida_util_estimada ?? 'N/A' }}"
                                    data-monitores="{{ $equipo->monitores->count() }}"
                                    data-discos="{{ $equipo->discosDuros->count() }}"
                                    data-ram="{{ $equipo->rams->pluck('capacidad_gb')->implode('GB, ') }}GB"
                                    data-perifericos="{{ $equipo->perifericos->pluck('tipo')->implode(', ') }}"
                                    data-procesadores="{{ $equipo->procesadores->count() }}">
                                    
                                    <td class="text-center font-weight-bold text-muted">{{ $equipo->id }}</td>
                                    <td>
                            
                                        @if(session('actualizado_id') == $equipo->id)
                                            <span class="badge badge-warning">Editado</span>
                                        @endif
                                        @if(session('new_id') == $equipo->id)
                                            <span class="badge badge-success">Nuevo Activo</span>
                                        @endif

                                        @if(session('new_mantenimiento') == $equipo->id)
                                            <span class="badge badge-secondary">Mantenimiento Registrado</span>
                                        @endif
                                        <div class="font-weight-bold text-dark">
                                        {{ $equipo->tipoActivo?->nombre ?? 'Sin Tipo' }} 
                                        {{ $equipo->marca?->nombre ?? 'Sin Marca' }}
                                        </div>
                                        <span class="secondary-data"><i class="fas fa-barcode mr-1"></i>{{ $equipo->serial }}</span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $equipo->usuario->name ?? 'Sin asignar' }}</div>
                                        <span class="secondary-data"><i class="fas fa-envelope mr-1"></i>{{ $equipo->usuario->email ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            @can('editar-equipo')
                                            <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-default text-warning" title="Editar"><i class="fas fa-pen"></i></a>
                                            @endcan
                                            @can('mantenimiento-equipo')
                                            <a href="{{ route('equipos.addwork', $equipo) }}" class="btn btn-sm btn-default text-primary" title="Mantenimiento"><i class="fas fa-tools"></i></a>
                                            @endcan
                                            @can('eliminar-equipo')
                                            <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-default text-danger" onclick="return confirm('¿Eliminar {{ $equipo->tipoActivo?->nombre ?? 'este equipo' }}?')"title="�Eliminar?"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endcan
                                                <a href="{{ route('equipos.show', ['uuid' => $equipo->id]) }}"
                                                class="btn btn-sm btn-info" 
                                                title="Ver Ficha Técnica">
                                                <i class="fas fa-eye"></i>
                                                </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    {{ $equipos->links() }}
                </div>
            </div>
        </div>

        {{-- PANEL DE DETALLE (LADO DERECHO) --}}
        <div class="col-xl-4 d-none d-xl-block">
            <div class="sticky-details">
                <div class="card detail-card shadow" id="panel-contenedor">
                    <div id="detail-content">
                        {{-- Estado inicial vacío --}}
                        <div class="empty-state-container">
                            <i class="fas fa-hand-pointer fa-4x text-light mb-4 d-block"></i>
                            <h5 class="text-muted">Vista Previa del Activo</h5>
                            <p class="small text-secondary">Desliza el puntero sobre una fila para ver el desglose completo del hardware y asignación.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventana Historial -->
    @if(request('show_toast'))
        <div id="floating-update-alert" class="position-fixed bottom-0 end-0 m-4" style="z-index: 1050; display: none;">
            <div class="card shadow-lg border-warning" style="width: 280px;">
                <div class="card-body p-3 text-center">
                    <div class="mb-2">
                        <i class="fas fa-check-circle text-warning fa-2x"></i>
                    </div>
                    <h6 class="fw-bold">¡Cambios Guardados!</h6>
                    <p class="small text-muted">¿Deseas verificar el historial de este equipo?</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('historial.index', ['buscar' => request('actualizado_id')]) }}" class="btn btn-warning btn-sm fw-bold">
                            <i class="fas fa-history me-1"></i> Ver Historial
                        </a>
                        <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted" onclick="document.getElementById('floating-update-alert').remove()">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mostrar con un pequeño delay
                setTimeout(() => {
                    const toast = document.getElementById('floating-update-alert');
                    toast.style.display = 'block';
                    toast.classList.add('animate__animated', 'animate__fadeInUp');
                }, 500);
            });
        </script>
    @endif


    @stop

@section('js')
<script>
$(document).ready(function() {
    $('.equipo-row').on('mouseenter', function() {
        const d = $(this).data();
        let contador = 1;
        // Estructura Profesional Dinamica
        const html = `
            <div class="animate-details">
                <div class="detail-header-premium">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge badge-light text-info mb-2">${d.tipo}</span>
                            <h3 class="font-weight-bold mb-0">${d.marca}</h3>
                            <p class="mb-0 opacity-8 text-sm"><i class="fas fa-hashtag mr-1"></i>ID: ${d.id} | <i class="fas fa-barcode mr-1"></i>${d.serial}</p>
                        </div>
                        <i class="fas fa-laptop-code fa-3x opacity-2"></i>
                    </div>
                </div>

                <div class="section-divider">Especificaciones Base</div>
                <div class="info-box-custom">
                    <span class="info-label">Sistema Operativo</span>
                    <span class="info-value text-info"><i class="fab fa-windows mr-1"></i>${d.so}</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label">Ubicación Actual</span>
                    <span class="info-value">${d.ubicacion}</span>
                </div>

                <div class="section-divider">Responsable del Activo</div>
                <div class="p-3 d-flex align-items-center">
                    <div class="bg-light rounded-circle p-3 mr-3">
                        <i class="fas fa-user-tie fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark">${d.usuario}</div>
                        <div class="small text-muted">${d.email}</div>
                    </div>
                </div>

                <div class="section-divider">Arquitectura de Hardware</div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-microchip mr-1"></i> Procesadores</span>
                    <span class="info-value badge badge-pill badge-secondary">${d.procesadores} unidades</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-memory mr-1"></i> Memoria RAM</span>
                    <span class="info-value">${d.ram}</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-hdd mr-1"></i> Almacenamiento</span>
                    <span class="info-value">${d.discos} Disco(s) Instalado(s)</span>
                </div>
                <div class="info-box-custom">
                    <span class="info-label"><i class="fas fa-desktop mr-1"></i> Monitores</span>
                    <span class="info-value">${d.monitores} Asignados</span>
                </div>
                <div class="p-3 border-bottom">
                    <span class="info-label d-block mb-2">Otros Periféricos</span>
                    <p class="small text-dark mb-0 font-italic"> ${d.perifericos || 'Sin periféricos adicionales registrados'}</p>
                </div>

                <div class="section-divider">Datos Económicos</div>
                <div class="p-3 bg-light">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small font-weight-bold text-muted">Valor de Adquisición</span>
                        <span class="text-success font-weight-bold">$${d.valor}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small font-weight-bold text-muted">Fecha Compra</span>
                        <span class="small">${d.fecha}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small font-weight-bold text-muted">Vida Útil Restante</span>
                        <span class="badge badge-warning text-dark">${d.vida} años</span>
                    </div>
                </div>
            </div>
        `;
        contador++;
        $('#detail-content').html(html);
    });

    // LÓGICA DE MENSAJES Y SCROLL PARA EL USUARIO
    const scrollId = "{{ session('new_id') ?? session('actualizado_id') }}";    
    if (scrollId) {
        const targetRow = document.getElementById('equipo-' + scrollId);
        if (targetRow) {
            targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            $(targetRow).css('background-color', '#fff3cd');
            setTimeout(() => {
                $(targetRow).animate({ backgroundColor: 'transparent' }, 2000);
            }, 1000);
        }
    }
});


$(document).ready(function() {
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);

        //Si se nota un cambio en la etiqueta <select>
        $select.on('change', function() {
            if ($(this).val() === 'OTRO_VALOR') {
                $input.fadeIn().focus();
            } else {
                $input.hide().val($(this).val()); 
            }
        });
    }
    setupSelectOtro('tipo_activo', 'tipo_input');
});
</script>
@stop