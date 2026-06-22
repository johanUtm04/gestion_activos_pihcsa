@extends('adminlte::page')

@section('title', 'Inventario de Vehículos')

@section('css')
<style>
    /* --- Estructura Dual Adaptativa --- */
    .inventory-wrapper {
        display: flex;
        gap: 20px;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        align-items: flex-start;
    }
    .table-container {
        flex: 1;
        transition: all 0.4s ease;
        min-width: 0; /* Previene desbordamientos de flexbox */
    }
    
    /* --- Panel Lateral Estilo Intel Preview --- */
    .preview-sidebar {
        width: 380px;
        position: sticky;
        top: 20px;
        display: none;
        opacity: 0;
        transform: translateX(30px);
        transition: opacity 0.35s ease, transform 0.35s ease;
    }
    .preview-sidebar.active {
        display: block;
        opacity: 1;
        transform: translateX(0);
    }

    /* --- Estructura y Limpieza de la Tabla --- */
    .table-assets thead th {
        background-color: #f8f9fa;
        color: #17a2b8;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        padding: 14px 10px;
    }
    .table-assets tbody tr {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .table-assets tbody tr:hover {
        background-color: rgba(23, 162, 184, 0.05) !important;
        transform: translateY(-1px);
        box-shadow: inset 4px 0 0 #17a2b8;
    }
    .table-assets tbody tr.selected-row {
        background-color: rgba(23, 162, 184, 0.08) !important;
        box-shadow: inset 4px 0 0 #117a8b;
    }
    .table-assets td {
        vertical-align: middle !important;
        padding: 12px 10px !important;
    }

    /* --- Tipografía y Datos Secundarios --- */
    .asset-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #212529;
    }
    .secondary-data {
        display: block;
        font-size: 0.78rem;
        color: #6c757d;
        margin-top: 3px;
    }
    .secondary-data i {
        width: 14px;
        text-align: center;
        color: #17a2b8;
    }

    /* --- Badges Estilizados de Mantenimiento --- */
    .badge-status {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 6px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: inline-block;
        min-width: 85px;
    }
    .badge-status-rojo { background-color: #fde8e8; color: #e02424; border: 1px solid #f8b4b4; }
    .badge-status-amarillo { background-color: #fef08a; color: #854d0e; border: 1px solid #fef08a; }
    .badge-status-verde { background-color: #def7ec; color: #03543f; border: 1px solid #bcf0da; }

    /* --- Botones de Acción Modificados --- */
    .btn-group-actions .btn {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #4a5568;
        transition: all 0.15s ease;
    }
    .btn-group-actions .btn:hover {
        background: #f7fafc;
    }
    .btn-group-actions .btn-edit:hover { color: #ffc107; border-color: #ffc107; }
    .btn-group-actions .btn-view:hover { color: #17a2b8; border-color: #17a2b8; }
    .btn-group-actions .btn-delete:hover { color: #dc3545; border-color: #dc3545; }

    /* --- Fila Inactiva --- */
    .row-inactive {
        background-color: #f8f9fa;
        opacity: 0.65;
    }
    .row-inactive:hover {
        box-shadow: inset 4px 0 0 #6c757d;
    }

    /* --- Medidores de Diagnóstico del Panel --- */
    .metric-progress {
        height: 6px;
        border-radius: 3px;
        background-color: #e9ecef;
        margin-top: 4px;
    }
    .metric-progress-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease-in-out;
    }

    /* --- Barra de desplazamiento premium --- */
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
</style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Inventario de Vehículos</h1>
            <small class="text-muted">Rol: <span class="badge badge-info px-2 py-1" style="font-size: 0.65rem; font-weight: 700;">ADMIN</span></small>
        </div>
        <div>
            <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-info font-weight-bold mr-1 shadow-sm" title="Ver Inventario de Equipos">
                <i class="fas fa-boxes mr-1"></i> Equipos
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold mr-1 shadow-sm">
                <i class="fas fa-ban mr-1"></i> Inactivos
            </button>
            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold mr-1 shadow-sm">
                <i class="fas fa-file-excel mr-1"></i> Reporte
            </button>
            <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm px-3" data-toggle="modal" data-target="#modalCrearVehiculo">
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
                    <h5 class="text-success font-weight-bold mb-0" style="font-size: 1.05rem;">¡Registro Completado!</h5>
                    <p class="mb-0 text-muted small">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Buscador colapsable mejorado --}}
    <div class="card card-outline card-info shadow-sm mb-3 collapsed-card">
        <div class="card-header p-2 d-flex align-items-center" data-card-widget="collapse" style="cursor: pointer;">
            <h3 class="card-title text-info font-weight-bold small mb-0 ml-2">
                <i class="fas fa-search-plus mr-1"></i> PANEL DE BÚSQUEDA AVANZADA
            </h3>
            <div class="card-tools ml-auto">
                <button type="button" class="btn btn-tool text-info"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body small" style="display: none;">
            {{-- Filtros del buscador --}}
        </div>
    </div>

    {{-- Contenedor de FlexBox Dinámico --}}
    <div class="inventory-wrapper">
        
        {{-- Contenedor Card Principal unificado --}}
        <div class="card card-outline card-info shadow-sm table-container">
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-hover table-assets mb-0" id="tablaVehiculos">
                        <thead>
                            <tr>
                                <th style="width: 60px" class="text-center">ID</th>
                                <th>Vehículo / Detalles</th>
                                <th>Asignación</th>
                                <th class="text-center" style="width: 130px;">Acciones</th>
                                <th class="text-center" style="width: 140px;">Mantenimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehiculos as $vehiculo)
                                <tr class="{{ !$vehiculo->is_active ? 'row-inactive' : '' }}" 
                                    data-id="{{ $vehiculo->id }}"
                                    data-tipo="{{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }}"
                                    data-marca="{{ $vehiculo->marca->nombre ?? 'N/A' }}"
                                    data-modelo="{{ $vehiculo->modelo }}"
                                    data-placas="{{ $vehiculo->placas ?? 'S/P' }}"
                                    data-usuario="{{ $vehiculo->usuario->name ?? 'Sin asignar' }}"
                                    data-email="{{ $vehiculo->usuario->email ?? 'N/A' }}"
                                    data-estatus="{{ $vehiculo->estatus_mantenimiento }}">
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
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                            {{ $vehiculo->usuario->name ?? 'Sin asignar' }}
                                        </div>
                                        @if($vehiculo->usuario)
                                            <span class="secondary-data">
                                                <i class="fas fa-envelope"></i> {{ $vehiculo->usuario->email }}
                                            </span>
                                        @else
                                            <span class="badge badge-warning px-2 py-0.5 mt-1" style="font-size: 0.65rem; font-weight: 700; color: #333;">Por asignar</span>
                                        @endif
                                    </td>
                                    <td class="text-center" onclick="event.stopPropagation();">
                                        <div class="btn-group btn-group-actions shadow-sm">
                                            <button type="button" class="btn btn-sm btn-edit btn-editar-vehiculo" data-id="{{ $vehiculo->id }}" title="Editar Parámetros">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-sm btn-view" title="Ver Ficha Técnica">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas dar de baja este vehículo del sistema?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-delete" title="Inactivar Activo">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($vehiculo->estatus_mantenimiento === 'rojo')
                                            <span class="badge-status badge-status-rojo"><i class="fas fa-exclamation-triangle mr-1"></i> Crítico</span>
                                        @elseif($vehiculo->estatus_mantenimiento === 'amarillo')
                                            <span class="badge-status badge-status-amarillo"><i class="fas fa-clock mr-1"></i> Próximo</span>
                                        @else
                                            <span class="badge-status badge-status-verde"><i class="fas fa-check mr-1"></i> Al Día</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 text-gray-200"></i>
                                            <p class="h6 font-weight-bold text-secondary">No se encontraron vehículos registrados</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Footer unificado --}}
            <div class="card-footer bg-white border-top-0 d-flex align-items-center justify-content-between py-2">
                <div>
                    @if(method_exists($vehiculos, 'links'))
                        {{ $vehiculos->links() }}
                    @endif
                </div>

                <div class="d-flex align-items-center ml-auto" style="opacity: 0.85;">
                    <div class="mx-2 d-none d-md-block" style="border-left: 1px solid #e2e8f0; height: 35px;"></div>
                    <div class="text-right mr-2 d-none d-lg-block">
                        <small class="text-muted d-block" style="font-size: 0.55rem; line-height: 1; letter-spacing: 0.8px; font-weight: 700;">SISTEMA DE GESTIÓN</small>
                        <span class="font-weight-bold text-dark" style="font-size: 0.75rem; letter-spacing: 0.3px;">ACTIVOS TI</span>
                    </div>
                    <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}" 
                         alt="Logo PIHCSA" 
                         style="height: 32px; width: auto; filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.08));">
                </div>
            </div>
        </div>

        {{-- Panel Lateral de Inspección Rápida --}}
        <div class="card card-outline card-info shadow-sm preview-sidebar" id="sidebarInspeccion">
            <div class="card-header p-3 d-flex align-items-center bg-light">
                <h3 class="card-title text-info font-weight-bold text-sm mb-0">
                    <i class="fas fa-satellite-dish mr-1 text-xs"></i> TELEMETRÍA DE ACTIVO
                </h3>
                <button type="button" class="close ml-auto" id="closeSidebar" style="outline:none; font-size: 1.2rem;">&times;</button>
            </div>
            <div class="card-body p-3">
                
                {{-- Bloque de Cabecera del Activo --}}
                <div class="text-center pb-3 border-bottom mb-3">
                    <div class="p-3 d-inline-block rounded-circle bg-light text-info mb-2 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1" id="sideVehiculo">---</h5>
                    <span class="badge badge-info px-2 py-1 font-weight-bold text-xs shadow-sm" id="sidePlacas">---</span>
                </div>

                {{-- Núcleo de Especificaciones --}}
                <div class="mb-3">
                    <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">Datos de Asignación</span>
                    <div class="bg-light p-2 rounded shadow-inner" style="font-size: 0.82rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Resguardante:</span>
                            <strong class="text-dark" id="sideUser">---</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Contacto:</span>
                            <span class="text-truncate text-muted font-weight-bold" id="sideEmail" style="max-width: 180px;">---</span>
                        </div>
                    </div>
                </div>

                {{-- Diagnóstico del Sistema Operativo --}}
                <div class="mb-3">
                    <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">Estatus de Salud</span>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span><i class="fas fa-microchip mr-1 text-muted"></i> Motor & Transmisión</span>
                            <strong id="barMotorText">0%</strong>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar" id="barMotor" style="width: 0%;"></div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span><i class="fas fa-circle-notch mr-1 text-muted"></i> Presión de Neumáticos</span>
                            <strong id="barTiresText">0%</strong>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar" id="barTires" style="width: 0%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span><i class="fas fa-oil-can mr-1 text-muted"></i> Niveles de Fluidos</span>
                            <strong id="barFluidsText">0%</strong>
                        </div>
                        <div class="metric-progress">
                            <div class="metric-progress-bar" id="barFluids" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>

                {{-- Geoposicionamiento --}}
                <div>
                    <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-1">Localización en Tiempo Real</span>
                    <div class="p-2 border rounded bg-dark d-flex align-items-center shadow-sm">
                        <i class="fas fa-map-marker-alt text-danger fa-pulse mr-2"></i>
                        <span class="text-xs text-monospace" style="color: #a3e635;">Morelia, Mich. (Live GPS)</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('vehiculos.modal_crear')
    @include('vehiculos.modal_editar')

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let catalogosCargados = false;
    let dataCatalogos = null;

    const sidebar = document.getElementById('sidebarInspeccion');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const rows = document.querySelectorAll('#tablaVehiculos tbody tr');

    /* --- Animación secuencial de entrada --- */
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(5px)';
        setTimeout(() => {
            row.style.transition = 'all 0.25s ease-in-out';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 30);
    });

    /* --- Mecánica de Selección y Carga en Panel Lateral --- */
    rows.forEach(row => {
        row.addEventListener('click', function() {
            if (this.classList.contains('row-inactive') || this.cells.length <= 1) return;

            // Cambiar clases de estado en las filas
            rows.forEach(r => r.classList.remove('selected-row'));
            this.classList.add('selected-row');

            // Mapeo de Atributos de Datos
            const tipo = this.getAttribute('data-tipo');
            const marca = this.getAttribute('data-marca');
            const modelo = this.getAttribute('data-modelo');
            const placas = this.getAttribute('data-placas');
            const usuario = this.getAttribute('data-usuario');
            const email = this.getAttribute('data-email');
            const estatus = this.getAttribute('data-estatus');

            // Inyectar datos en el panel
            document.getElementById('sideVehiculo').innerText = `${tipo} ${marca} (${modelo})`;
            document.getElementById('sidePlacas').innerText = `Placas: ${placas}`;
            document.getElementById('sideUser').innerText = usuario;
            document.getElementById('sideEmail').innerText = email;

            // Determinar métricas basadas en el estatus de mantenimiento
            let motorVal, tiresVal, fluidsVal, progressClass;
            if (estatus === 'rojo') {
                motorVal = 42; tiresVal = 55; fluidsVal = 30;
                progressClass = 'bg-danger';
            } else if (estatus === 'amarillo') {
                motorVal = 78; tiresVal = 80; fluidsVal = 72;
                progressClass = 'bg-warning';
            } else {
                motorVal = 98; tiresVal = 95; fluidsVal = 96;
                progressClass = 'bg-success';
            }

            // Actualizar barras
            updateMetric('barMotor', motorVal, progressClass);
            updateMetric('barTires', tiresVal, progressClass);
            updateMetric('barFluids', fluidsVal, progressClass);

            // Desplegar el Panel Lateral
            sidebar.classList.add('active');
        });
    });

    function updateMetric(id, val, bgClass) {
        const bar = document.getElementById(id);
        const text = document.getElementById(`${id}Text`);
        bar.className = 'metric-progress-bar ' + bgClass;
        bar.style.width = '0%';
        text.innerText = '0%';
        setTimeout(() => {
            bar.style.width = val + '%';
            text.innerText = val + '%';
        }, 50);
    }

    /* --- Control de Cierre del Panel --- */
    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', function() {
            sidebar.classList.remove('active');
            rows.forEach(r => r.classList.remove('selected-row'));
        });
    }

    /* --- Lógica Interna de Catálogos (Filtros & Modales) --- */
    function cargarOpciones(selectId, items, propNombre, selectedId = null) {
        const select = document.getElementById(selectId);
        if(!select) return;
        select.innerHTML = '<option value="" disabled selected>Selecciona una opción...</option>';
        items.forEach(item => {
            const isSelected = selectedId && item.id == selectedId ? 'selected' : '';
            select.innerHTML += `<option value="${item.id}" ${isSelected}>${item[propNombre]}</option>`;
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

    /* --- EVENTO CORREGIDO PARA EDITAR VEHÍCULO --- */
    document.getElementById('tablaVehiculos').addEventListener('click', function(e) {
        const btnEditar = e.target.closest('.btn-editar-vehiculo');
        
        if (btnEditar) {
            e.preventDefault();
            e.stopPropagation(); // Evita detonar la selección de fila
            const vehiculoId = btnEditar.getAttribute('data-id');

            prefetchCatalogos(() => {
                fetch(`/vehiculos/${vehiculoId}/edit`)
                    .then(response => response.json())
                    .then(vehiculo => {
                        // Rellenar campos estándar
                        document.getElementById('edit_vehiculo_id').value = vehiculo.id;
                        document.getElementById('edit_modelo').value = vehiculo.modelo;
                        document.getElementById('edit_anio').value = vehiculo.anio;
                        document.getElementById('edit_placas').value = vehiculo.placas || '';
                        document.getElementById('edit_no_serie').value = vehiculo.no_serie || '';
                        document.getElementById('edit_no_motor').value = vehiculo.no_motor || '';
                        
                        // Cargar selects primarios
                        cargarOpciones('edit_tipo_vehiculo_id', dataCatalogos.tipos, 'nombre', vehiculo.tipo_vehiculo_id);
                        cargarOpciones('edit_marca_id', dataCatalogos.marcas, 'nombre', vehiculo.marca_id);
                        
                        // Cargar resguardantes y locaciones
                        cargarOpciones('edit_usuario_id', dataCatalogos.usuarios || [], 'name', vehiculo.usuario_id);
                        cargarOpciones('edit_ubicacion_id', dataCatalogos.ubicaciones || [], 'nombre', vehiculo.ubicacion_id);

                        // Estatus Operativo Activo/Inactivo
                        const selectActive = document.getElementById('edit_is_active');
                        const contenedorMotivo = document.getElementById('contenedor_motivo');
                        const inputMotivo = document.getElementById('edit_motivo_inactivacion');

                        selectActive.value = vehiculo.is_active;
                        if (vehiculo.is_active == 0) {
                            contenedorMotivo.style.display = 'block';
                            inputMotivo.value = vehiculo.motivo_inactivacion || '';
                            inputMotivo.required = true;
                        } else {
                            contenedorMotivo.style.display = 'none';
                            inputMotivo.value = '';
                            inputMotivo.required = false;
                        }
                        
                        const formEditar = document.getElementById('formEditarVehiculo');
                        if(formEditar) {
                            formEditar.action = `/vehiculos/${vehiculoId}`;
                        }

                        $('#modalEditarVehiculo').modal('show');
                    })
                    .catch(error => console.error('Error al obtener datos del vehículo:', error));
            });
        }
    });

    /* --- LISTENER INTERACTIVO PARA ESTADO OPERATIVO EN MODAL EDITAR --- */
    const editActiveSelect = document.getElementById('edit_is_active');
    if (editActiveSelect) {
        editActiveSelect.addEventListener('change', function() {
            const contenedor = document.getElementById('contenedor_motivo');
            const input = document.getElementById('edit_motivo_inactivacion');
            
            if (this.value == '0') {
                contenedor.style.display = 'block';
                input.required = true;
                input.focus();
            } else {
                contenedor.style.display = 'none';
                input.required = false;
                input.value = '';
            }
        });
    }

    /* --- Evento Modal Crear --- */
    $('#modalCrearVehiculo').on('show.bs.modal', function () {
        prefetchCatalogos(() => {
            cargarOpciones('tipo_vehiculo_id', dataCatalogos.tipos, 'nombre');
            cargarOpciones('marca_id', dataCatalogos.marcas, 'nombre');
        });
    });
});
</script>
@stop