@extends('adminlte::page')

@section('title', 'Inventario de Vehículos')

@section('css')
<style>
    /* Sincronización Estricta con el Estilo Visual de Activos PIHCSA */
    .table-custom-pihcsa {
        border-collapse: separate;
        border-spacing: 0 8px; /* Crea el efecto de filas separadas */
    }
    .table-custom-pihcsa thead th {
        border: none !important;
        color: #17a2b8; /* Turquesa característico de tus cabeceras */
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        padding-bottom: 12px;
    }
    .table-custom-pihcsa tbody tr {
        background-color: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    .table-custom-pihcsa tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px);
    }
    .table-custom-pihcsa tbody td {
        border-top: 1px solid #e3e6f0 !important;
        border-bottom: 1px solid #e3e6f0 !important;
        padding: 14px 12px;
        vertical-align: middle !important;
    }
    .table-custom-pihcsa tbody tr td:first-child {
        border-left: 1px solid #e3e6f0 !important;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .table-custom-pihcsa tbody tr td:last-child {
        border-right: 1px solid #e3e6f0 !important;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    .secondary-info {
        display: block;
        font-size: 0.75rem;
        color: #858796;
        margin-top: 2px;
    }
    /* Limpieza de los inputs de DataTables para que no rompan el layout */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
    }
</style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">Inventario</h1>
            <small class="text-muted">Rol: <span class="badge badge-info px-2 py-1" style="font-size: 0.65rem;">ADMIN</span></small>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold mr-1">
                <i class="fas fa-ban mr-1"></i> Inactivos
            </button>
            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold mr-1">
                <i class="fas fa-file-excel mr-1"></i> Reporte
            </button>
            <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalCrearVehiculo">
                <i class="fas fa-plus mr-1"></i> Nuevo
            </button>
        </div>
    </div>
@stop

@section('content')

    @if(session('success'))
        <div class="callout callout-success alert alert-dismissible fade show shadow-sm border-0 mb-3" role="alert"
             style="border-left: 4px solid #28a745 !important; background-color: #ffffff;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="opacity: 0.5; outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="d-flex align-items-center">
                <div class="text-success mr-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <h5 class="text-success font-weight-bold mb-0" style="font-size: 1.1rem;">¡Registro Completado!</h5>
                    <p class="mb-0 text-muted small">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="card card-outline card-info shadow-sm mb-3 collapsed-card">
        <div class="card-header p-2 d-flex align-items-center" data-card-widget="collapse" style="cursor: pointer;">
            <h3 class="card-title text-info font-weight-bold small mb-0 ml-2">
                <i class="fas fa-search mr-1"></i> PANEL DE BÚSQUEDA AVANZADA
            </h3>
            <div class="card-tools ml-auto">
                <button type="button" class="btn btn-tool text-info"><i class="fas fa-plus"></i></button>
            </div>
        </div>
        <div class="card-body small" style="display: none;">
            </div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom-pihcsa w-100 mb-0" id="tablaVehiculos">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Activo / Serial</th>
                    <th>Asignado A</th>
                    <th class="text-center">Acciones</th>
                    <th class="text-center">Mantenimiento Anual</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehiculos as $vehiculo)
                    <tr class="{{ !$vehiculo->is_active ? 'bg-light text-muted' : '' }}">
                        <td class="font-weight-bold text-secondary text-center">
                            {{ $vehiculo->id }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="badge badge-success px-1 mb-1 shadow-xs" style="font-size: 0.65rem;">Nuevo Vehículo</span>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                                        {{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }} {{ $vehiculo->marca->nombre ?? 'N/A' }}
                                    </div>
                                    <span class="secondary-info">
                                        <i class="fas fa-barcode mr-1 text-muted"></i> {{ $vehiculo->modelo }} — Placas: <strong>{{ $vehiculo->placas ?? 'S/P' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                                {{ $vehiculo->usuario->name ?? 'Sin asignar' }}
                            </div>
                            @if($vehiculo->usuario)
                                <span class="secondary-info"><i class="fas fa-envelope mr-1 text-muted"></i> {{ $vehiculo->usuario->email ?? '' }}</span>
                            @else
                                <span class="badge badge-warning px-2 py-1 text-xs text-dark mt-1">Por asignar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-clean text-warning btn-editar-vehiculo" data-id="{{ $vehiculo->id }}" data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-sm btn-clean text-info" data-toggle="tooltip" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este vehículo?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-clean text-danger" data-toggle="tooltip" title="Eliminar">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($vehiculo->estatus_mantenimiento === 'rojo')
                                <span class="badge badge-danger font-weight-bold p-2 text-uppercase shadow-xs" style="min-width: 85px; font-size: 0.7rem;">Crítico</span>
                            @elseif($vehiculo->estatus_mantenimiento === 'amarillo')
                                <span class="badge badge-warning text-dark font-weight-bold p-2 text-uppercase shadow-xs" style="min-width: 85px; font-size: 0.7rem;">Próximo</span>
                            @else
                                <span class="badge badge-success font-weight-bold p-2 text-uppercase shadow-xs" style="min-width: 85px; font-size: 0.7rem;">Al Día</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 bg-white border rounded">
                            <div class="text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p class="mb-0">No hay registros de vehículos disponibles.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('vehiculos.modal_crear')
    @include('vehiculos.modal_editar')

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let catalogosCargados = false;
    let dataCatalogos = null;

    function cargarOpciones(selectId, items, propNombre, selectedId = null) {
        const select = document.getElementById(selectId);
        if(!select) return;
        select.innerHTML = '<option value="" disabled>Selecciona una opción...</option>';
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

    $('#modalCrearVehiculo').on('show.bs.modal', function () {
        prefetchCatalogos(() => {
            cargarOpciones('tipo_vehiculo_id', dataCatalogos.tipos, 'nombre');
            cargarOpciones('marca_id', dataCatalogos.marcas, 'nombre');
            cargarOpciones('usuario_id', dataCatalogos.usuarios, 'name');
            cargarOpciones('ubicacion_id', dataCatalogos.ubicaciones, 'nombre');
        });
    });

    const selectActive = document.getElementById('edit_is_active');
    const divMotivo = document.getElementById('contenedor_motivo');
    const inputMotivo = document.getElementById('edit_motivo_inactivacion');

    if(selectActive) {
        selectActive.addEventListener('change', function() {
            if (this.value == '0') {
                if(divMotivo) divMotivo.style.display = 'block';
                if(inputMotivo) inputMotivo.setAttribute('required', 'required');
            } else {
                if(divMotivo) divMotivo.style.display = 'none';
                if(inputMotivo) {
                    inputMotivo.removeAttribute('required');
                    inputMotivo.value = '';
                }
            }
        });
    }

    $(document).on('click', '.btn-editar-vehiculo', function () {
        const vehiculoId = this.getAttribute('data-id');
        
        prefetchCatalogos(() => {
            fetch(`/vehiculos/${vehiculoId}`)
                .then(response => response.json().catch(() => null))
                .then(vehiculo => {
                    if (!vehiculo) {
                        document.getElementById('formEditarVehiculo').action = `/vehiculos/${vehiculoId}`;
                        $('#modalEditarVehiculo').modal('show');
                        return;
                    }

                    document.getElementById('edit_modelo').value = vehiculo.modelo;
                    document.getElementById('edit_anio').value = vehiculo.anio;
                    document.getElementById('edit_placas').value = vehiculo.placas || '';
                    document.getElementById('edit_no_serie').value = vehiculo.no_serie || '';
                    document.getElementById('edit_no_motor').value = vehiculo.no_motor || '';
                    document.getElementById('edit_is_active').value = vehiculo.is_active;

                    if (vehiculo.is_active == 0) {
                        if(divMotivo) divMotivo.style.display = 'block';
                        if(inputMotivo) {
                            inputMotivo.value = vehiculo.motivo_inactivacion || '';
                            inputMotivo.setAttribute('required', 'required');
                        }
                    } else {
                        if(divMotivo) divMotivo.style.display = 'none';
                        if(inputMotivo) inputMotivo.removeAttribute('required');
                    }

                    cargarOpciones('edit_tipo_vehiculo_id', dataCatalogos.tipos, 'nombre', vehiculo.tipo_vehiculo_id);
                    cargarOpciones('edit_marca_id', dataCatalogos.marcas, 'nombre', vehiculo.marca_id);
                    cargarOpciones('edit_usuario_id', dataCatalogos.usuarios, 'name', vehiculo.usuario_id);
                    cargarOpciones('edit_ubicacion_id', dataCatalogos.ubicaciones, 'nombre', vehiculo.ubicacion_id);

                    document.getElementById('formEditarVehiculo').action = `/vehiculos/${vehiculoId}`;
                    $('#modalEditarVehiculo').modal('show');
                });
        });
    });

    if ($.fn.DataTable.isDataTable('#tablaVehiculos')) {
        $('#tablaVehiculos').DataTable().destroy();
    }
    $('#tablaVehiculos').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": false,
        "responsive": true,
        "dom": '<"d-flex justify-content-between align-items-center mb-2"f>t<"d-flex justify-content-between align-items-center mt-2"p>',
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        }
    });

    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@stop