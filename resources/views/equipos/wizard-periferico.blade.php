@extends('adminlte::page')

@section('title', 'Wizard | Asignar Periférico')

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
        color: #17a2b8;
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
                <i class="fas fa-keyboard text-info"></i> Periféricos
            </h1>
        </div>

        <a href="{{ route('equipos.wizard-ram', $uuid) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left"></i> Anterior
        </a>
    </div>
</div>

{{-- WIZARD SIMULACION (MIGAJAS MANTENIDAS) --}}
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

            <div class="wizard-step active">
            <a href="{{ route('equipos.wizard-periferico', $uuid) }}">
              <i class="fas fa-mouse"></i> 
                <div>Periferico</div>
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

<div class="card card-outline card-info">
    <div class="card-body">

        <form action="{{ route('equipos.wizard.savePeriferico', $uuid) }}" method="POST" id="perifericoForm">
            @csrf

            <fieldset class="fieldset-group">

                <legend class="mb-3">
                    <i class="fas fa-keyboard"></i> Datos del Periférico
                </legend>

                {{-- Silueta --}}
                <div class="text-center mb-4 text-muted">
                    <i class="fas fa-mouse fa-3x"></i>
                    <div class="small mt-1">Accesorio externo</div>
                </div>

                <div class="row">
                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="tipo_select"><i class="fas fa-mouse-pointer"></i> Tipo de Periférico</label>
                            <select id="tipo_select" class="form-control">
                                <option value="" selected>Seleccione tipo</option>
                                <option value="Teclado">Teclado</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Combo (Teclado+Mouse)">Combo (Teclado+Mouse)</option>
                                <option value="Webcam">Webcam</option>
                                <option value="Diadema / Headset">Diadema / Headset</option>
                                <option value="OTRO_VALOR">-- Otro (Escribir) --</option>
                            </select>
                            
                            <input type="text" name="tipo" id="tipo_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. Scanner, Impresora, etc."
                                   value="{{ old('tipo', session('wizard_equipo.periferico.tipo')) }}">
                            @error('tipo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="marca_select"><i class="fas fa-tag"></i> Marca</label>
                            <select id="marca_select" class="form-control">
                                <option value="" selected>Seleccione marca</option>
                                <option value="Logitech">Logitech</option>
                                <option value="HP">HP</option>
                                <option value="Dell">Dell</option>
                                <option value="Lenovo">Lenovo</option>
                                <option value="Genius">Genius</option>
                                <option value="Microsoft">Microsoft</option>
                                <option value="OTRO_VALOR">-- Otra marca (Escribir) --</option>
                            </select>

                            <input type="text" name="marca" id="marca_input" 
                                   class="form-control custom-input" 
                                   placeholder="Escriba la marca..."
                                   value="{{ old('marca', session('wizard_equipo.periferico.marca')) }}">
                            @error('marca') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="serial">
                                <i class="fas fa-barcode"></i> Serial
                            </label>
                            <input type="text"
                                   id="serial"
                                   name="serial"
                                   class="form-control"
                                   value="{{ old('serial', session('wizard_equipo.periferico.serial')) }}"
                                   placeholder="Número de serie">
                            @error('serial') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="interface_select"><i class="fas fa-plug"></i> Interfaz</label>
                            <select id="interface_select" class="form-control">
                                <option value="">Seleccione interfaz</option>
                                <option value="USB">USB</option>
                                <option value="Bluetooth">Bluetooth</option>
                                <option value="Inalámbrico (Receptor USB)">Inalámbrico (Receptor USB)</option>
                                <option value="Jack 3.5mm">Jack 3.5mm</option>
                                <option value="PS/2">PS/2</option>
                                <option value="OTRO_VALOR">-- Otra interfaz (Escribir) --</option>
                            </select>

                            <input type="text" name="interface" id="interface_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. USB-C, etc."
                                   value="{{ old('interface', session('wizard_equipo.periferico.interface')) }}">
                            @error('interface') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

            </fieldset>

            {{-- FOOTER --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('equipos.wizard-procesador', $uuid) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-fast-forward"></i> Omitir Periféricos
                </a>

                <button type="submit" class="btn btn-info btn-lg px-5">
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
        
        let initialVal = $input.val();
        if(initialVal && !$select.find(`option[value='${initialVal}']`).length && initialVal !== '') {
            $select.val('OTRO_VALOR');
            $input.show();
        } else if (initialVal !== '') {
            $select.val(initialVal);
        }
    }

    setupSelectOtro('tipo_select', 'tipo_input');
    setupSelectOtro('marca_select', 'marca_input');
    setupSelectOtro('interface_select', 'interface_input');
});
</script>
@stop