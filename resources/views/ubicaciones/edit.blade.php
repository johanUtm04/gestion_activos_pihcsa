@extends('adminlte::page')

@section('title', 'Editar Ubicación | Activos TI')

@section('css')
<style>
    .section-title {
        border-bottom: 2px solid #dc3545;
        padding-bottom: 5px;
        margin-bottom: 15px;
        color: #dc3545;
        font-weight: 600;
    }

    .data-item {
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: none; 
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }
    
    fieldset.border {
        border: 1px solid #dee2e6 !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        border-radius: 8px;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-map-marker-alt text-red-pure"></i>
        Edición de Ubicación: {{ strtoupper($ubicacion->nombre) }}
    </h1>

    <a href="{{ route('ubicaciones.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestión de ubicaciones
    </a>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-5">
            <div class="card card-outline card-red-pure shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list"></i> Detalle Actual
                    </h3>
                </div>

                <div class="card-body">
                    <fieldset class="border p-3 mb-4">
                        <legend class="w-auto px-2 text-red-pure">
                            <i class="fas fa-map"></i> Información de Ubicación
                        </legend>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-id-badge"></i> ID:
                            </span>
                            <span class="float-right text-muted">{{ $ubicacion->id }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-map-marker-alt"></i> Nombre:
                            </span>
                            <span class="float-right">{{ $ubicacion->nombre }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-barcode"></i> Código:
                            </span>
                            <span class="float-right font-weight-bold">{{ $ubicacion->codigo }}</span>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card card-outline card-red-pure shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-pen-square"></i> Modificación de Datos
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('ubicaciones.update', $ubicacion) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2 text-red-pure">
                                <i class="fas fa-info-circle"></i> Datos Base
                            </legend>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nombre">
                                        <i class="fas fa-map-marker-alt text-red-pure"></i> Nombre
                                    </label>
                                    <input type="text" name="nombre" id="nombre" class="form-control"
                                           value="{{ old('nombre', $ubicacion->nombre) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="codigo">
                                        <i class="fas fa-barcode text-red-pure"></i> Código
                                    </label>
                                    <input type="text" name="codigo" id="codigo" class="form-control"
                                           value="{{ old('codigo', $ubicacion->codigo) }}">
                                </div>
                            </div>
                        </fieldset>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-red-pure btn-lg btn-block shadow">
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
