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
            <a href="{{ route('equipos.wizard.ubicacion', $uuid) }}">
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

                            <option value="" disabled {{ old('departamento') == '' ? 'selected' : '' }}>
                                -- Seleccione --
                            </option>
                            
                            <option value="ALMACEN" {{ old('departamento') == 'ALMACEN' ? 'selected' : '' }}>ALMACEN</option>
                            <option value="ASIST_PAGOS" {{ old('departamento') == 'ASIST_PAGOS' ? 'selected' : '' }}>ASIST. PAGOS</option>
                            <option value="COBRANZA" {{ old('departamento') == 'COBRANZA' ? 'selected' : '' }}>COBRANZA</option>
                            <option value="AUDITORIA" {{ old('departamento') == 'AUDITORIA' ? 'selected' : '' }}>AUDITORIA</option>
                            <option value="AUXILIAR_ADMINISTRATIVO" {{ old('departamento') == 'AUXILIAR_ADMINISTRATIVO' ? 'selected' : '' }}>AUXILIAR ADMINISTRATIVO</option>
                            <option value="AUXILIAR_LOGISTICA" {{ old('departamento') == 'AUXILIAR_LOGISTICA' ? 'selected' : '' }}>AUXILIAR LOGISTICA</option>
                            <option value="CALIDAD" {{ old('departamento') == 'CALIDAD' ? 'selected' : '' }}>CALIDAD</option>
                            <option value="COBRANZA_GOB" {{ old('departamento') == 'COBRANZA_GOB' ? 'selected' : '' }}>COBRANZA GOB</option>
                            <option value="COMPRAS" {{ old('departamento') == 'COMPRAS' ? 'selected' : '' }}>COMPRAS</option>
                            <option value="CONTABILIDAD" {{ old('departamento') == 'CONTABILIDAD' ? 'selected' : '' }}>CONTABILIDAD</option>
                            <option value="COPIADORA" {{ old('departamento') == 'COPIADORA' ? 'selected' : '' }}>COPIADORA</option>
                            <option value="COSTOS" {{ old('departamento') == 'COSTOS' ? 'selected' : '' }}>COSTOS</option>
                            <option value="CREDITO" {{ old('departamento') == 'CREDITO' ? 'selected' : '' }}>CREDITO</option>
                            <option value="CULTURA_TALENTO" {{ old('departamento') == 'CULTURA_TALENTO' ? 'selected' : '' }}>CULTURA Y TALENTO</option>
                            <option value="EMBARQUES" {{ old('departamento') == 'EMBARQUES' ? 'selected' : '' }}>EMBARQUES</option>
                            <option value="ETIQUETAS" {{ old('departamento') == 'ETIQUETAS' ? 'selected' : '' }}>ETIQUETAS</option>
                            <option value="FACTURACION" {{ old('departamento') == 'FACTURACION' ? 'selected' : '' }}>FACTURACION</option>
                            <option value="JURIDICO" {{ old('departamento') == 'JURIDICO' ? 'selected' : '' }}>JURIDICO</option>
                            <option value="LOGISTICA" {{ old('departamento') == 'LOGISTICA' ? 'selected' : '' }}>LOGISTICA</option>
                            <option value="NOMINAS" {{ old('departamento') == 'NOMINAS' ? 'selected' : '' }}>NOMINAS</option>
                            <option value="OPERACIONES" {{ old('departamento') == 'OPERACIONES' ? 'selected' : '' }}>OPERACIONES</option>
                            <option value="RECEPCION" {{ old('departamento') == 'RECEPCION' ? 'selected' : '' }}>RECEPCION</option>
                            <option value="RECEPCION_COMPRAS" {{ old('departamento') == 'RECEPCION_COMPRAS' ? 'selected' : '' }}>RECEPCION DE COMPRAS</option>
                            <option value="RECEPCION_MATERIAL" {{ old('departamento') == 'RECEPCION_MATERIAL' ? 'selected' : '' }}>RECEPCION DE MATERIAL</option>
                            <option value="RESPONSABLE_SANITARIO" {{ old('departamento') == 'RESPONSABLE_SANITARIO' ? 'selected' : '' }}>RESPONSABLE SANITARIO</option>
                            <option value="SISTEMAS" {{ old('departamento') == 'SISTEMAS' ? 'selected' : '' }}>SISTEMAS</option>
                            <option value="SITE" {{ old('departamento') == 'SITE' ? 'selected' : '' }}>SITE</option>
                            <option value="VENTAS_GOB" {{ old('departamento') == 'VENTAS_GOB' ? 'selected' : '' }}>VENTAS GOB</option>
                            <option value="VENTAS_PRIV" {{ old('departamento') == 'VENTAS_PRIV' ? 'selected' : '' }}>VENTAS PRIV</option>
                            <option value="VIGILANCIA" {{ old('departamento') == 'VIGILANCIA' ? 'selected' : '' }}>VIGILANCIA</option>

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
