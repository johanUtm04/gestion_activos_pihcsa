@extends('adminlte::page')

@section('title', 'Configuración de Tasas')

@section('content_header')
    <h1><i class="fas fa-percent mr-2 text-info"></i>Catálogo de Tasas LISR</h1>
@stop

@section('content')
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
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-block shadow-sm font-weight-bold">Guardar Tasa</button>
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
                            <th style="width: 120px text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasas as $tasa)
                        <tr>
                            <td class="font-weight-bold">{{ $tasa->nombre }}</td>
                            <td><span class="badge badge-info p-2">{{ $tasa->porcentaje }}%</span></td>
                            <td class="small text-muted">{{ $tasa->descripcion }}</td>
                            <td class="text-right">
                                {{-- BOTÓN EDITAR --}}
                                <button class="btn btn-xs btn-outline-primary shadow-sm px-2" 
                                        data-toggle="modal" 
                                        data-target="#modalEditarTasa"
                                        data-id="{{ $tasa->id }}"
                                        data-nombre="{{ $tasa->nombre }}"
                                        data-porcentaje="{{ $tasa->porcentaje }}"
                                        data-descripcion="{{ $tasa->descripcion }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- BOTÓN ELIMINAR --}}
                                <form action="{{ route('tasas.destroy', $tasa->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger shadow-sm px-2" onclick="return confirm('¿Eliminar esta tasa?')">
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

{{-- MODAL DE EDICIÓN --}}
<div class="modal fade" id="modalEditarTasa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Editar Tasa</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditarTasa" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Concepto</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Porcentaje Anual (%)</label>
                        <input type="number" name="porcentaje" id="edit_porcentaje" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold">Actualizar Tasa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$('#modalEditarTasa').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    
    var nombre = button.data('nombre');
    var porcentaje = button.data('porcentaje');
    var descripcion = button.data('descripcion');

    var modal = $(this);
    
    var urlAccion = "{{ url('configuracion/tasas') }}/" + id;
    
    modal.find('#formEditarTasa').attr('action', urlAccion);
    
    modal.find('#edit_nombre').val(nombre);
    modal.find('#edit_porcentaje').val(porcentaje);
    modal.find('#edit_descripcion').val(descripcion);
});
</script>
@stop