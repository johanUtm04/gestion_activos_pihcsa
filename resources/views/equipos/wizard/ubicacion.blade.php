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
<div class="card mb-3 shadow-sm">
    <div class="card-body p-3 d-flex align-items-center">
        
        {{-- Sección de Pasos --}}
        <div class="flex-grow-1">
            <div class="d-flex justify-content-around text-center wizard-steps">
                
                {{-- Paso Completado --}}
                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.create') }}" class="text-success decoration-none">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Activo</div>
                    </a>
                </div>

                {{-- Paso Activo --}}
                <div class="wizard-step active">
                    <a href="{{ route('equipos.wizard.ubicacion', $uuid) }}" class="text-primary font-weight-bold decoration-none">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="small">Ubicación</div>
                    </a>
                </div>

                {{-- Pasos Pendientes --}}
                <div class="wizard-step text-muted">
                    <i class="fas fa-microchip"></i>
                    <div class="small">Componentes</div>
                </div>

                <div class="wizard-step text-muted">
                    <i class="fas fa-flag-checkered"></i>
                    <div class="small">Final</div>
                </div>

            </div>
        </div>

        <div class="mx-3 d-none d-md-block" style="border-left: 1px solid #e0e0e0; height: 45px;"></div>

        <div class="d-flex align-items-center ml-auto" style="opacity: 0.9;">
            <div class="text-right mr-2 d-none d-lg-block">
                <small class="text-muted d-block" style="font-size: 0.55rem; line-height: 1; letter-spacing: 0.5px;">SISTEMA DE GESTIÓN</small>
                <span class="font-weight-bold text-secondary" style="font-size: 0.75rem;">ACTIVOS TI</span>
            </div>
            <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}" 
                 alt="Logo PIHCSA" 
                 style="height: 40px; width: auto; filter: drop-shadow(0px 2px 2px rgba(0,0,0,0.1));">
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

        <div class="row">
            {{-- Ubicación --}}
            <div class="col-md-6">
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
            </div>

                {{-- Departamento --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="departamento">
                            <i class="fas fa-building"></i> Departamento *
                        </label>

                        <select name="departamento_perteneciente" id="departamento_perteneciente"
                                class="form-control select2" required>

                            <option value="" disabled {{ !old('departamento_perteneciente', $equipo['departamento_perteneciente'] ?? '') ? 'selected' : '' }}>
                                -- Seleccione --
                            </option>

                            @php
                                $departamentos = [
                                    'ADMINISTRACION', 'ALMACEN', 'CALIDAD', 'COBRANZA', 'COMPRAS', 
                                    'CONTABILIDAD', 'CREDITO', 'CULTURA Y TALENTO', 'DIRECCION', 
                                    'EMBARQUES', 'INVENTARIOS', 'JURIDICO', 'LOGISTICA', 
                                    'SISTEMAS', 'VENTAS'
                                ];
                            @endphp

                            @foreach($departamentos as $dep)
                                <option value="{{ $dep }}" 
                                    {{ old('departamento_perteneciente', $equipo['departamento_perteneciente'] ?? '') == $dep ? 'selected' : '' }}>
                                    {{ $dep }}
                                </option>
                            @endforeach
                        </select>

                        <small class="form-text text-muted">
                            Selecciona el departamento responsable del activo.
                        </small>
                    </div>
                </div>
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
