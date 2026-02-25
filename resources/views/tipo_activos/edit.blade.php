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
        /* Adiós línea punteada */
        border-bottom: none;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }

    /* Estándar para los contenedores fieldset */
    fieldset.border {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px;
        background-color: #fdfdfd;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-microchip text-danger"></i> 
        Edición de Categoría: {{ strtoupper($tipo_activo->nombre) }}
    </h1>
    <a href="{{ route('tipo_activos.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left"></i> Volver al listado
    </a>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- COLUMNA IZQUIERDA: VISTA PREVIA --}}
            <div class="col-md-5">
                <div class="card card-outline card-danger shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-eye"></i> Vista Previa Actual</h3>
                    </div>
                    <div class="card-body">
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2 text-danger font-weight-bold">
                                <i class="fas fa-info-circle"></i> Detalles del Catálogo
                            </legend>

                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-fingerprint"></i> ID de Registro:</span> 
                                <span class="float-right font-weight-bold text-muted">{{ $tipo_activo->id }}</span>
                            </div>

                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-laptop"></i> Nombre Actual:</span> 
                                <span class="float-right text-uppercase font-weight-bold">{{ $tipo_activo->nombre }}</span>
                            </div>

                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-clock"></i> Frecuencia Alerta:</span> 
                                <span class="float-right font-weight-bold text-primary">
                                    {{ $tipo_activo->frecuencia_meses ?? 0 }} Meses
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-boxes"></i> Equipos vinculados:</span> 
                                <span class="float-right badge badge-danger">
                                    {{ $tipo_activo->equipos->count() }} Activos
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label"><i class="fas fa-history"></i> Última actualización:</span> 
                                <span class="float-right text-muted">
                                    {{ $tipo_activo->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: FORMULARIO --}}
            <div class="col-md-7">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit"></i> Modificar Categoría</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('tipo_activos.update', $tipo_activo) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto px-3 text-primary font-weight-bold">
                                    <i class="fas fa-cog"></i> Configuración del Activo
                                </legend>

                                <div class="form-group">
                                    <label for="nombre"><i class="fas fa-pen-nib"></i> Nombre de la Categoría: </label>
                                    <input type="text" name="nombre" id="nombre" 
                                           class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $tipo_activo->nombre) }}"
                                           placeholder="Ej: LAPTOP, PC ESCRITORIO, SERVIDOR..." required>
                                    @error('nombre')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <label for="frecuencia_meses">
                                        <i class="fas fa-tools text-muted"></i> Alerta de Mantenimiento Preventivo (Meses):
                                    </label>
                                    <input type="number" name="frecuencia_meses" id="frecuencia_meses" min="0" max="48"
                                           class="form-control form-control-lg @error('frecuencia_meses') is-invalid @enderror"
                                           value="{{ old('frecuencia_meses', $tipo_activo->frecuencia_meses) }}"
                                           placeholder="0 = Sin alerta">
                                    <small class="text-muted">Establece cada cuántos meses el sistema marcará el equipo para revisión técnica.</small>
                                    @error('frecuencia_meses')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </fieldset>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block shadow">
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