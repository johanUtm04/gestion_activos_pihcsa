@extends('adminlte::page')

@section('title', 'Wizard | Asignar Procesador')

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
        color: #28a745;
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

    .custom-input { display: none; margin-top: 10px; }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold mb-1">
                <i class="fas fa-microchip text-success"></i> Procesador (CPU)
            </h1>
        </div>

        <a href="{{ route('equipos.wizard-periferico', $uuid) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left"></i> Anterior
        </a>
    </div>
</div>

{{-- WIZARD SIMULACION (MANTENIENDO TUS MIGAJAS) --}}
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

            <div class="wizard-step completed">
            <a href="{{ route('equipos.wizard-ram', $uuid) }}">
               <i class="fas fa-memory"></i>
                <div>Ram</div>
            </a>
            </div>

            <div class="wizard-step completed">
            <a href="{{ route('equipos.wizard-periferico', $uuid) }}">
              <i class="fas fa-mouse"></i> 
                <div>Periferico</div>
            </a>
            </div>

            <div class="wizard-step active">
            <a href="{{ route('equipos.wizard-procesador', $uuid) }}">
              <i class="fas fa-microchip"></i>
                <div>Procesador</div>
            </a>
            </div>

        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-success">
    <div class="card-body">

        <form action="{{ route('equipos.wizard.saveProcesador', $uuid) }}" method="POST" id="procesadorForm">
            @csrf

            <fieldset class="fieldset-group">

                <legend class="mb-3">
                    <i class="fas fa-microchip"></i> Datos del Procesador
                </legend>

                {{-- Silueta --}}
                <div class="text-center mb-4 text-muted">
                    <i class="fas fa-microchip fa-3x"></i>
                    <div class="small mt-1">Unidad central de procesamiento</div>
                </div>

                <div class="row">
                    {{-- Marca --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="marca_select"><i class="fas fa-tag"></i> Marca CPU</label>
                            <select id="marca_select" class="form-control">
                                <option value="">Seleccione marca</option>
                                <option value="Intel">Intel</option>
                                <option value="AMD">AMD</option>
                                <option value="Apple">Apple (M1/M2/M3)</option>
                                <option value="OTRO_VALOR">-- Otra marca (Escribir) --</option>
                            </select>
                            <input type="text" name="marca" id="marca_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. Qualcom, IBM"
                                   value="{{ old('marca', session('wizard_equipo.procesador.marca')) }}">
                            @error('marca') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Modelo --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="desc_select"><i class="fas fa-list-alt"></i> Modelo / Descripción</label>
                            <select id="desc_select" class="form-control">
                                <option value="">Seleccione modelo</option>
                                <optgroup label="Intel">
                                    <option value="Core i3">Core i3</option>
                                    <option value="Core i5">Core i5</option>
                                    <option value="Core i7">Core i7</option>
                                    <option value="Core i9">Core i9</option>
                                    <option value="Celeron / Pentium">Celeron / Pentium</option>
                                    <option value="Xeon">Xeon (Servidor)</option>
                                </optgroup>
                                <optgroup label="AMD">
                                    <option value="Ryzen 3">Ryzen 3</option>
                                    <option value="Ryzen 5">Ryzen 5</option>
                                    <option value="Ryzen 7">Ryzen 7</option>
                                    <option value="Ryzen 9">Ryzen 9</option>
                                    <option value="Athlon">Athlon</option>
                                    <option value="EPYC">EPYC</option>
                                </optgroup>
                                <option value="OTRO_VALOR">-- Otro (Escribir específico) --</option>
                            </select>
                            <input type="text" name="descripcion_tipo" id="desc_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. Core i7-11700K"
                                   value="{{ old('descripcion_tipo', session('wizard_equipo.procesador.descripcion_tipo')) }}">
                            @error('descripcion_tipo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Frecuencia del Micro --}}
                    <!-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="frec_select"><i class="fas fa-tachometer-alt"></i> Frecuencia</label>
                            <select id="frec_select" class="form-control">
                                <option value="">Seleccione frecuencia</option>
                                <option value="1.10 GHz">1.10 GHz</option>
                                <option value="2.00 GHz">2.00 GHz</option>
                                <option value="2.40 GHz">2.40 GHz</option>
                                <option value="2.60 GHz">2.60 GHz</option>
                                <option value="3.00 GHz">3.00 GHz</option>
                                <option value="3.20 GHz">3.20 GHz</option>
                                <option value="3.60 GHz">3.60 GHz</option>
                                <option value="4.20 GHz">4.20 GHz</option>
                                <option value="OTRO_VALOR">-- Otra (Escribir) --</option>
                            </select>
                            <input type="text" name="frecuenciaMicro" id="frec_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. 2.9 GHz"
                                   value="{{ old('frecuenciaMicro', session('wizard_equipo.procesador.frecuenciaMicro')) }}">
                            @error('frecuenciaMicro') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div> -->
                </div>

            </fieldset>

            {{-- FOOTER FINAL --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                    <i class="fas fa-check-double"></i> Finalizar y Guardar Activo
                </button>
            </div>

        </form>

    </div>
</div>

@stop

@section('js')
<script>
$(document).ready(function() {
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

        // Al cargar
        let initialVal = $input.val();
        if(initialVal && !$select.find(`option[value='${initialVal}']`).length && initialVal !== '') {
            $select.val('OTRO_VALOR');
            $input.show();
        } else if (initialVal !== '') {
            $select.val(initialVal);
        }
    }

    setupSelectOtro('marca_select', 'marca_input');
    setupSelectOtro('desc_select', 'desc_input');
    setupSelectOtro('frec_select', 'frec_input');
});
</script>
@stop