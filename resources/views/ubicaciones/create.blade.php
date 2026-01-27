@extends('adminlte::page')

@section('title', 'Registrar Ubicación')

@section('css')
<style>
    .fieldset-group {
        border: 1px solid #f5c6cb;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: .25rem;
        background-color: #ffffff;
    }

    .fieldset-group legend {
        width: inherit;
        padding: 0 10px;
        border-bottom: none;
        font-size: 1.1em;
        font-weight: 600;
        color: #dc3545;
    }

    .form-group label {
        font-weight: 500;
    }

    .wizard-step {
        color: #adb5bd;
        font-size: 14px;
    }

    .wizard-step.active {
        color: #dc3545;
        font-weight: 600;
    }

    .wizard-step i {
        font-size: 22px;
        display: block;
        margin-bottom: 4px;
    }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <h1 class="font-weight-bold mb-1">
        <i class="fas fa-map-marker-alt text-danger"></i> Registrar Nueva Ubicación
    </h1>
    <a href="{{ route('ubicaciones.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestion de ubicaciones
    </a>
</div>


{{-- WIZARD SIMPLE --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <div class="d-flex justify-content-around text-center">
            <div class="wizard-step active">
                <i class="fas fa-map"></i>
                <div>Ubicación</div>
            </div>
            <div class="wizard-step">
                <i class="fas fa-check-circle"></i>
                <div>Confirmar</div>
            </div>
        </div>
    </div>
</div>
@stop

@section('content')

{{-- ERRORES --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <strong><i class="fas fa-exclamation-triangle"></i> Revisa los datos</strong>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('ubicaciones.store') }}" method="POST">
@csrf

<div class="card card-outline card-danger">
    <div class="card-body">

        <fieldset class="fieldset-group">
            <legend><i class="fas fa-info-circle"></i> Información de la Ubicación</legend>

            {{-- ICONO --}}
            <div class="text-center mb-4 text-muted">
                <i class="fas fa-building fa-3x"></i>
                <div class="small mt-1">Datos generales</div>
            </div>

            <div class="form-group">
                <label>Nombre de la ubicación *</label>
                <input type="text"
                       name="nombre"
                       class="form-control"
                       placeholder="Ej. Oficina Central"
                       value="{{ old('nombre') }}"
                       required>
            </div>

            <div class="form-group">
                <label>Código *</label>
                <input type="text"
                       name="codigo"
                       class="form-control"
                       placeholder="Ej. OFI-01"
                       value="{{ old('codigo') }}"
                       required>
                <small class="text-muted">
                    Identificador corto de la ubicación
                </small>
            </div>
        </fieldset>

    </div>

    {{-- FOOTER --}}
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-danger btn-lg">
            <i class="fas fa-save"></i> Guardar Ubicación
        </button>

        <a href="{{ route('ubicaciones.index') }}" class="btn btn-secondary btn-lg">
            Cancelar
        </a>
    </div>
</div>
</form>

@stop
