@extends('adminlte::page')

@section('title', 'Editar Área | Activos TI')

@section('css')
<style>
    .data-item {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: none;
    }

    .data-label {
        font-weight: 600;
        color: #495057;
    }

    fieldset.border {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px;
        background-color: #fdfdfd;
    }
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-building" style="color: #E83E8C;"></i>
        Edición de Área: {{ strtoupper($empresa->nombre) }}
    </h1>

    <a href="{{ route('empresas.index') }}"
       class="btn btn-secondary btn-sm shadow-sm">

        <i class="fas fa-arrow-left"></i>
        Volver al catálogo de áreas
    </a>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- COLUMNA IZQUIERDA: INFORMACIÓN ACTUAL --}}
            <div class="col-md-5">
                <div class="card card-outline shadow-sm"
                     style="border-top: 3px solid #E83E8C;">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Estado Actual en Catálogo
                        </h3>
                    </div>

                    <div class="card-body">
                        <fieldset class="border p-3 mb-4">
                            <legend class="w-auto px-2 font-weight-bold"
                                    style="color: #E83E8C;">

                                <i class="fas fa-building"></i>
                                Información Registrada
                            </legend>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-id-card"></i>
                                    ID del Sistema:
                                </span>

                                <span class="float-right font-weight-bold">
                                    {{ $empresa->id }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-building"></i>
                                    Nombre Actual:
                                </span>

                                <span class="float-right text-muted text-uppercase">
                                    {{ $empresa->nombre }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-align-left"></i>
                                    Descripción Actual:
                                </span>

                                <span class="float-right text-muted">
                                    {{ $empresa->rfc ?: 'Sin descripción' }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha de Registro:
                                </span>

                                <span class="float-right">
                                    {{ $empresa->created_at?->format('d/m/Y H:i') }}
                                </span>
                            </div>

                            <div class="data-item">
                                <span class="data-label">
                                    <i class="fas fa-history"></i>
                                    Última Actualización:
                                </span>

                                <span class="float-right text-muted">
                                    {{ $empresa->updated_at?->diffForHumans() }}
                                </span>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: FORMULARIO --}}
            <div class="col-md-7">
                <div class="card card-outline shadow-sm"
                     style="border-top: 3px solid #E83E8C;">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i>
                            Actualizar Datos del Área
                        </h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('empresas.update', $empresa->id) }}"
                              method="POST">

                            @csrf
                            @method('PUT')

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto px-2 font-weight-bold"
                                        style="color: #E83E8C;">

                                    <i class="fas fa-database"></i>
                                    Datos del Área
                                </legend>

                                <div class="form-group">
                                    <label for="nombre">
                                        <i class="fas fa-file-signature"></i>
                                        Nombre del Área:
                                    </label>

                                    <input type="text"
                                           name="nombre"
                                           id="nombre"
                                           class="form-control form-control-lg @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $empresa->nombre) }}"
                                           placeholder="Ej: RECURSOS HUMANOS, SISTEMAS, CONTABILIDAD..."
                                           required>

                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <p class="text-muted mt-2 small">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        El cambio de nombre se reflejará en los registros asociados a esta área.
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label for="rfc">
                                        <i class="fas fa-align-left"></i>
                                        Descripción:
                                    </label>

                                    <input type="text"
                                           name="rfc"
                                           id="rfc"
                                           class="form-control form-control-lg @error('rfc') is-invalid @enderror"
                                           value="{{ old('rfc', $empresa->rfc) }}"
                                           placeholder="Ej: Área encargada de la gestión administrativa..."
                                           maxlength="13">

                                    @error('rfc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <p class="text-muted mt-2 small">
                                        <i class="fas fa-info-circle text-info"></i>
                                        Agrega una descripción breve que permita identificar el área.
                                    </p>
                                </div>
                            </fieldset>

                            <div class="mt-4">
                                <button type="submit"
                                        class="btn btn-lg btn-block shadow font-weight-bold"
                                        style="background-color: #E83E8C;
                                               border-color: #E83E8C;
                                               color: #ffffff;">

                                    <i class="fas fa-sync-alt"></i>
                                    Guardar Cambios en Catálogo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop