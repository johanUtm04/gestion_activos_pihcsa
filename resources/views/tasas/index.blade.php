@extends('adminlte::page')

@section('title', 'Configuración de Tasas')

@section('content_header')
    <h1><i class="fas fa-percent mr-2 text-info"></i>Catálogo de Tasas LISR</h1>
@stop

@section('content')
<div class="container-fluid">
    
    {{-- MARCADOR PARA SCROLL AUTOMÁTICO (Solo aparece si hay una sesión de éxito) --}}
    @if(session('tasa_id'))
        <div id="scroll-target-marker" data-id="{{ session('tasa_id') }}"></div>
    @endif

    {{-- ALERTAS DE SISTEMA --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 5px solid #1e7e34 !important;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close text-dark"><span aria-hidden="true">&times;</span></button>
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-3 fa-2x text-success"></i>
                <div><span class="font-weight-bold text-dark">¡Hecho!</span><br><span class="text-dark">{{ session('success') }}</span></div>
            </div>
        </div>
    @endif

    @if(session('actualizado'))
        <div class="alert alert-primary alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 5px solid #0056b3 !important;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close text-dark"><span aria-hidden="true">&times;</span></button>
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle mr-3 fa-2x text-primary"></i>
                <div><span class="font-weight-bold text-dark">Actualización</span><br><span class="text-dark">{{ session('actualizado') }}</span></div>
            </div>
        </div>
    @endif

    <div class="row">
        {{-- FORMULARIO DE CREACIÓN --}}
        <div class="col-md-4">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header"><h3 class="card-title font-weight-bold">Nueva Tasa</h3></div>
                <form action="{{ route('tasas.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nombre del Concepto</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Equipo de Cómputo" required>
                        </div>
                        <div class="form-group">
                            <label>Porcentaje Anual (%)</label>
                            <input type="number" name="porcentaje" class="form-control" step="0.01" placeholder="30.00" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3" placeholder="Opcional..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info btn-block shadow-sm font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Guardar Tasa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLA DE REGISTROS --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Concepto</th>
                                <th>Tasa %</th>
                                <th>Descripción</th>
                                <th class="text-center" style="width: 120px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasas as $tasa)
                            {{-- ID DINÁMICO PARA EL SCROLL --}}
                            <tr id="marca-{{ $tasa->id }}">
                                <td class="font-weight-bold text-dark">{{ $tasa->nombre }}</td>
                                <td><span class="badge badge-info px-3 py-2">{{ $tasa->porcentaje }}%</span></td>
                                <td class="small text-muted">{{ $tasa->descripcion ?? 'Sin descripción' }}</td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-outline-primary shadow-sm mr-1" 
                                            data-toggle="modal" data-target="#modalEditarTasa"
                                            data-id="{{ $tasa->id }}" data-nombre="{{ $tasa->nombre }}"
                                            data-porcentaje="{{ $tasa->porcentaje }}" data-descripcion="{{ $tasa->descripcion }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('tasas.destroy', $tasa->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('¿Eliminar esta tasa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE EDICIÓN --}}
<div class="modal fade" id="modalEditarTasa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Editar Tasa</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditarTasa" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="form-group"><label>Nombre</label><input type="text" name="nombre" id="edit_nombre" class="form-control" required></div>
                    <div class="form-group"><label>Porcentaje (%)</label><input type="number" name="porcentaje" id="edit_porcentaje" class="form-control" step="0.01" required></div>
                    <div class="form-group"><label>Descripción</label><textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3"></textarea></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // 1. LÓGICA DE RESALTADO Y SCROLL
    const marker = document.getElementById('scroll-target-marker');
    if (marker) {
        const tasaId = marker.getAttribute('data-id');
        const targetRow = document.getElementById('marca-' + tasaId);

        if (targetRow) {
            // Scroll suave al elemento
            targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Color de fondo temporal (puedes usar #e3f2fd para azul o #fdecea para rojo claro)
            $(targetRow).css('background-color', '#e3f2fd');
            
            // Efecto de parpadeo
            $(targetRow).fadeOut(400).fadeIn(400).fadeOut(400).fadeIn(400, function() {
                setTimeout(() => {
                    $(this).animate({ backgroundColor: "transparent" }, 2000);
                }, 3000);
            });
        }
    }

    // 2. LÓGICA DEL MODAL DE EDICIÓN
    $('#modalEditarTasa').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        
        modal.find('#formEditarTasa').attr('action', "{{ url('configuracion/tasas') }}/" + id);
        modal.find('#edit_nombre').val(button.data('nombre'));
        modal.find('#edit_porcentaje').val(button.data('porcentaje'));
        modal.find('#edit_descripcion').val(button.data('descripcion'));
    });
});
</script>
@stop