@extends('adminlte::page')

@section('title', 'Editar Departamento | Activos TI')

@section('css')
<style>
    .data-item { margin-bottom: 10px; padding-bottom: 5px; border-bottom: none; }
    .data-label { font-weight: 600; color: #495057; }
    fieldset.border {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px;
        background-color: #fdfdfd;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-building" style="color: #FD7E14;"></i>
        Edición de Departamento: {{ strtoupper($departamento->nombre) }}
    </h1>
    <a href="{{ route('departamentos.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="card card-outline shadow-sm" style="border-top: 3px solid #FD7E14;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-eye"></i> Vista Previa Actual</h3>
                </div>
                <div class="card-body">
                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto px-2 font-weight-bold" style="color: #FD7E14;">
                            <i class="fas fa-info-circle"></i> Detalles del Catálogo
                        </legend>

                        <div class="data-item">
                            <span class="data-label"><i class="fas fa-fingerprint"></i> ID de Registro:</span>
                            <span class="float-right font-weight-bold text-muted">{{ $departamento->id }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label"><i class="fas fa-building"></i> Nombre Actual:</span>
                            <span class="float-right text-uppercase font-weight-bold">{{ $departamento->nombre }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label"><i class="fas fa-boxes"></i> Equipos vinculados:</span>
                            <span class="float-right badge text-white" style="background-color: #FD7E14;">
                                {{ $departamento->equiposCount() }} Activos
                            </span>
                        </div>

                        <div class="data-item">
                            <span class="data-label"><i class="fas fa-history"></i> Última actualización:</span>
                            <span class="float-right text-muted">
                                {{ $departamento->updated_at?->diffForHumans() }}
                            </span>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card card-outline shadow-sm" style="border-top: 3px solid #FD7E14;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> Modificar Departamento</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('departamentos.update', $departamento) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <fieldset class="border p-4 mb-4">
                            <legend class="w-auto px-3 font-weight-bold" style="color: #FD7E14;">
                                <i class="fas fa-cog"></i> Datos del Departamento
                            </legend>

                            <div class="form-group">
                                <label for="nombre"><i class="fas fa-pen-nib"></i> Nombre del Departamento: </label>
                                <input type="text" name="nombre" id="nombre"
                                       class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre', $departamento->nombre) }}"
                                       placeholder="Ej: SISTEMAS, VENTAS..." required>
                                @error('nombre')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                <p class="text-muted mt-2 small">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Los equipos ya registrados con el nombre anterior conservarán ese texto; solo los nuevos registros usarán el nombre actualizado.
                                </p>
                            </div>
                        </fieldset>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-lg btn-block shadow text-white" style="background-color: #FD7E14; border-color: #FD7E14;">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
