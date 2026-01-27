@extends('adminlte::page')

@section('title', 'Editar Tipo de Activo | Activos TI')

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
        padding-bottom: 5px;
        border-bottom: 1px dashed #ced4da;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-microchip text-danger"></i> 
        Edici�n de Categor�a: {{ strtoupper($tipo_activo->nombre) }}
    </h1>
    <a href="{{ route('tipo_activos.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-5">
                <div class="card card-outline card-danger shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-eye"></i> Vista Previa Actual
                        </h3>
                    </div>
                    <div class="card-body">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle"></i> Detalles del Cat�logo
                        </h5>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-fingerprint"></i> ID de Registro:
                            </span> 
                            <span class="float-right font-weight-bold">{{ $tipo_activo->id }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-laptop"></i> Nombre Actual:
                            </span> 
                            <span class="float-right text-uppercase">{{ $tipo_activo->nombre }}</span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-boxes"></i> Equipos bajo este tipo:
                            </span> 
                            <span class="float-right badge badge-danger">
                                {{ $tipo_activo->equipos->count() }} Activos
                            </span>
                        </div>

                        <div class="data-item">
                            <span class="data-label">
                                <i class="fas fa-clock"></i> �ltima actualizaci�n:
                            </span> 
                            <span class="float-right text-muted">
                                {{ $tipo_activo->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> Modificar Categor�a
                        </h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('tipo_activos.update', $tipo_activo) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <fieldset class="border p-4 mb-4" style="border-radius: 8px; background-color: #fdfdfd;">
                                <legend class="w-auto px-3 text-primary font-weight-bold">
                                    <i class="fas fa-cog"></i> Configuraci�n del Activo
                                </legend>

                                <div class="form-group">
                                    <label for="nombre"><i class="fas fa-pen-nib"></i> Nombre de la Categor�a: </label>
                                    <input type="text" name="nombre" id="nombre" 
                                           class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $tipo_activo->nombre) }}"
                                           placeholder="Ej: LAPTOP, PC ESCRITORIO, SERVIDOR..."
                                           required>
                                    
                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    
                                </div>
                            </fieldset>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block shadow">
                                    <i class="fas fa-save"></i> Actualizar Tipo de Activo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@section('footer')
    <div class="text-center text-muted">
        <small>M�dulo de Gesti�n de Cat�logos TI &copy; {{ date('Y') }}</small>
    </div>
@stop