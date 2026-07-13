@extends('adminlte::page')

@section('title', 'Editar Marca | Activos TI')

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
        /* Línea punteada quitada */
        border-bottom: none;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }

    /* Mejora visual para los fieldsets */
    fieldset.border {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-tags text-danger"></i> 
        Edición de Marca: {{ strtoupper($marca->nombre) }}
    </h1>
    <a href="{{ route('marcas.index') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left"></i> Volver a gestión de marcas
    </a>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-5">
                <div class="card card-outline card-danger shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Estado Actual en Catálogo
                        </h3>
                    </div>
                    <div class="card-body">
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2 text-danger font-weight-bold">
                                <i class="fas fa-industry"></i> Información Registrada
                            </legend>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-id-card"></i> ID del Sistema:
                                </span> 
                                <span class="float-right font-weight-bold">{{ $marca->id }}</span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-tag"></i> Nombre Actual:
                                </span> 
                                <span class="float-right text-muted">{{ $marca->nombre }}</span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-calendar-alt"></i> Fecha Registro:
                                </span> 
                                <span class="float-right">{{ $marca->created_at?->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-laptop"></i> Equipos Vinculados:
                                </span> 
                                <span class="float-right badge badge-info">{{ $marca->equipos->count() }} equipos</span>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card card-outline card-danger shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> Actualizar Nombre del Fabricante
                        </h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('marcas.update', $marca) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto px-2 text-danger font-weight-bold">
                                    <i class="fas fa-database"></i> Datos de la Marca
                                </legend>

                                <div class="form-group">
                                    <label for="nombre"><i class="fas fa-file-signature"></i> Nombre de la Marca: </label>
                                    <input type="text" name="nombre" id="nombre" 
                                           class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $marca->nombre) }}"
                                           placeholder="Ej: LENOVO, HP, DELL..."
                                           required>
                                    
                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    
                                    <p class="text-muted mt-2 small">
                                        <i class="fas fa-exclamation-triangle text-warning"></i> 
                                        Nota: El cambio de nombre se reflejará en todos los inventarios de equipos asociados.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label for="tipo">
                                        <i class="fas fa-layer-group"></i> Tipo de Marca:
                                    </label>

                                    <select name="tipo"
                                            id="tipo"
                                            class="form-control form-control-lg @error('tipo') is-invalid @enderror"
                                            required>
                                        <option value="">Selecciona el tipo...</option>

                                        <option value="TI" {{ old('tipo', $marca->tipo) === 'TI' ? 'selected' : '' }}>
                                            TI / Equipo de cómputo
                                        </option>

                                        <option value="AUTO" {{ old('tipo', $marca->tipo) === 'AUTO' ? 'selected' : '' }}>
                                            AUTO / Vehículos
                                        </option>
                                    </select>

                                    @error('tipo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <p class="text-muted mt-2 small">
                                        <i class="fas fa-info-circle text-info"></i>
                                        Este campo define si la marca aparecerá en equipos de TI o en vehículos.
                                    </p>
                                </div>
                            </fieldset>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger btn-lg btn-block shadow">
                                    <i class="fas fa-sync-alt"></i> Guardar Cambios en Catálogo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop