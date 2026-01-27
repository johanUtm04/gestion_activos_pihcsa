@extends('adminlte::page')

@section('title', 'Wizard | Asignar RAM')

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
        color: #ffc107;
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

    /* Estilo para los campos de "Otro" que inician ocultos */
    .custom-input { display: none; margin-top: 10px; }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold mb-1">
                <i class="fas fa-memory text-warning"></i> Memoria RAM
            </h1>
        </div>

        <a href="{{ route('equipos.wizard-discos_duros', $uuid) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left"></i> Anterior
        </a>
    </div>
</div>

{{-- WIZARD SIMULACION (TUS MIGAJAS) --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between text-center wizard-steps">

            <div class="wizard-step completed">
                <a href="{{ route('equipos.wizard.create') }}">
                    <i class="fas fa-desktop"></i>
                    <div>Activo</div>
                </a>
            </div>

            <div class="wizard-step completed">
            <a href="{{ route('equipos.wizard-ubicacion', $uuid) }}">
                <i class="fas fa-map-marker-alt"></i>
                <div>Ubicacion</div>
            </a>
            </div>

            <div class="wizard-step completed">
            <a href="{{ route('equipos.wizard-monitores', $uuid) }}">
                <i class="fas fa-tv"></i>
                <div>Monitor</div>
            </a>
            </div>

            <div class="wizard-step completed">
            <a href="{{ route('equipos.wizard-discos_duros', $uuid) }}">
                <i class="fas fa-hdd"></i>
                <div>Disco Duro</div>
            </a>
            </div>

            <div class="wizard-step active">
            <a href="{{ route('equipos.wizard-ram', $uuid) }}">
               <i class="fas fa-memory"></i>
                <div>Ram</div>
            </a>
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

<div class="card card-outline card-warning">
    <div class="card-body">

        <form action="{{ route('equipos.wizard.saveRam', $uuid) }}" method="POST" id="ramForm">
            @csrf

            <fieldset class="fieldset-group">

                <legend class="mb-3">
                    <i class="fas fa-memory"></i> Datos de la Memoria RAM
                </legend>

                {{-- Silueta --}}
                <div class="text-center mb-4 text-muted">
                    <i class="fas fa-microchip fa-3x"></i>
                    <div class="small mt-1">Módulo de memoria</div>
                </div>

                <div class="row">
                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="capacidad_select"><i class="fas fa-tachometer-alt"></i> Capacidad (GB)</label>
                            <select id="capacidad_select" class="form-control">
                                <option value="" selected>Seleccione capacidad</option>
                                <option value="4">4 GB</option>
                                <option value="8">8 GB</option>
                                <option value="12">12 GB</option>
                                <option value="16">16 GB</option>
                                <option value="32">32 GB</option>
                                <option value="64">64 GB</option>
                                <option value="128">128 GB</option>
                                <option value="OTRO_VALOR">-- Otra capacidad (Escribir) --</option>
                            </select>
                            
                            <input type="text" name="capacidad_gb" id="capacidad_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. 2, 24, etc."
                                   value="{{ old('capacidad_gb', session('wizard_equipo.ram.capacidad_gb')) }}">
                            @error('capacidad_gb') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="clock_select"><i class="fas fa-clock"></i> Velocidad (MHz)</label>
                            <select id="clock_select" class="form-control">
                                <option value="">Seleccione velocidad</option>
                                <optgroup label="DDR3">
                                    <option value="1333">1333 MHz</option>
                                    <option value="1600">1600 MHz</option>
                                </optgroup>
                                <optgroup label="DDR4">
                                    <option value="2133">2133 MHz</option>
                                    <option value="2400">2400 MHz</option>
                                    <option value="2666">2666 MHz</option>
                                    <option value="3200">3200 MHz</option>
                                </optgroup>
                                <optgroup label="DDR5">
                                    <option value="4800">4800 MHz</option>
                                    <option value="5200">5200 MHz</option>
                                    <option value="6000">6000 MHz</option>
                                </optgroup>
                                <option value="OTRO_VALOR">-- Otra velocidad (Escribir) --</option>
                            </select>

                            <input type="text" name="clock_mhz" id="clock_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. 1066, 3600"
                                   value="{{ old('clock_mhz', session('wizard_equipo.ram.clock_mhz')) }}">
                            @error('clock_mhz') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="tipo_select"><i class="fas fa-sitemap"></i> Tipo / Generación</label>
                            <select id="tipo_select" class="form-control">
                                <option value="">Seleccione tipo</option>
                                <option value="DDR3">DDR3</option>
                                <option value="DDR3L">DDR3L (Low Voltage)</option>
                                <option value="DDR4">DDR4</option>
                                <option value="DDR5">DDR5</option>
                                <option value="LPDDR4">LPDDR4 (Integrada)</option>
                                <option value="OTRO_VALOR">-- Otro tipo (Escribir) --</option>
                            </select>

                            <input type="text" name="tipo_chz" id="tipo_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. SDRAM, DDR2"
                                   value="{{ old('tipo_chz', session('wizard_equipo.ram.tipo_chz')) }}">
                            @error('tipo_chz') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

            </fieldset>

            {{-- FOOTER --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('equipos.wizard-periferico', $uuid) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-fast-forward"></i> Omitir RAM
                </a>

                <button type="submit" class="btn btn-warning btn-lg px-5">
                    <i class="fas fa-arrow-right"></i> Continuar
                </button>
            </div>

        </form>

    </div>
</div>

@stop

@section('js')
<script>
$(document).ready(function() {
    /**
     * Sincronización de Selects e Inputs manuales
     */
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);

        $select.on('change', function() {
            if ($(this).val() === 'OTRO_VALOR') {
                $input.fadeIn().focus();
            } else {
                $input.hide().val($(this).val()); 
            }
        });

        // Sincronización al cargar página (Old values / Session)
        let initialVal = $input.val();
        if(initialVal && !$select.find(`option[value='${initialVal}']`).length && initialVal !== '') {
            $select.val('OTRO_VALOR');
            $input.show();
        } else if (initialVal !== '') {
            $select.val(initialVal);
        }
    }

    setupSelectOtro('capacidad_select', 'capacidad_input');
    setupSelectOtro('clock_select', 'clock_input');
    setupSelectOtro('tipo_select', 'tipo_input');
});
</script>
@stop