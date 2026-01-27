@extends('adminlte::page')

@section('title', 'Wizard | Asignar Ubicación')

@section('css')
<style>
    .wizard-steps {
        font-size: 14px;
    }

    .wizard-step {
        color: #adb5bd;
    }

    .wizard-step i {
        font-size: 22px;
        margin-bottom: 4px;
        display: block;
    }

    .wizard-step.active {
        color: #007bff;
        font-weight: 600;
    }

    .wizard-step.completed {
        color: #28a745;
    }

    .fieldset-group {
        border: 1px solid #ced4da;
        padding: 25px;
        border-radius: .25rem;
        background-color: #ffffff;
    }

    .fieldset-group i.fa-3x {
        opacity: 0.25;
    }
</style>
@stop

@section('content_header')
<div class="mb-3">
<div class="d-flex justify-content-between align-items-center">
    <h1 class="font-weight-bold mb-1">
        <i class="fas fa-map-marker-alt text-info"></i> Asignar Ubicación
    </h1>
        <a href="{{ route('equipos.wizard.create') }}" class="btn btn-outline-secondary">
        <i class="fas fa-chevron-left"></i> Anterior
    </a>
        </div>
</div>

{{-- WIZARD SIMULACION --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between text-center wizard-steps">

            <div class="wizard-step completed">

            <a href="{{ route('equipos.wizard.create') }}">
                <i class="fas fa-desktop"></i>
                <div>Activo</div>
            </a>

            </div>

            <div class="wizard-step active">
            <a href="{{ route('equipos.wizard-ubicacion', $uuid) }}">
                <i class="fas fa-map-marker-alt"></i>
                <div>Ubicacion</div>
            </a>
            </div>

            <div class="wizard-step">
                <i class="fas fa-microchip"></i>
                <div>Componentes</div>
            </div>

            <div class="wizard-step">
                <i class="fas fa-flag-checkered"></i>
                <div>Final</div>
            </div>

        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-primary">
    <div class="card-body">
        <form action="{{ route('equipos.wizard.saveUbicacion', $uuid) }}" method="POST">
            @csrf

            <fieldset class="fieldset-group">

                <legend class="mb-3">
                    <i class="fas fa-warehouse"></i> Ubicación del Activo
                </legend>

                {{-- Silueta --}}
                <div class="text-center mb-4 text-muted">
                    <i class="fas fa-building fa-3x"></i>
                    <div class="small mt-1">Área física asignada</div>
                </div>

                {{-- Ubicación --}}
                <div class="form-group">
                    <label for="ubicacion_id">
                        <i class="fas fa-map-signs"></i> Selecciona la ubicación *
                    </label>

                    <select name="ubicacion_id" id="ubicacion_id"
                            class="form-control select2" required>
                        <option value="">Buscar o seleccionar ubicación…</option>
                        @foreach(\App\Models\Ubicacion::all() as $ubicacion)
                            <option value="{{ $ubicacion->id }}">
                                {{ $ubicacion->nombre }} — CP {{ $ubicacion->codigo }}
                            </option>
                        @endforeach
                    </select>

                    <small class="form-text text-muted">
                        Define dónde se encuentra el activo dentro de la organización.
                    </small>
                </div>

            </fieldset>

            {{-- FOOTER --}}
            <div class="text-right mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right"></i> Continuar
                </button>
            </div>

        </form>
    </div>
</div>

@stop

@section('js')
<script>
    $(document).ready(function () {
        $('#ubicacion_id').select2({
            theme: 'bootstrap4',
            placeholder: 'Buscar o seleccionar ubicación…',
            allowClear: true
        });
    });
</script>
@stop
