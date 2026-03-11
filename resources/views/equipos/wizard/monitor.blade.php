@extends('adminlte::page')

@section('title', 'Wizard | Asignar Monitor')

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
                <i class="fas fa-tv text-success"></i> Monitor
            </h1>
        </div>

        <a href="{{ route('equipos.wizard.ubicacion', $uuid) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left"></i> Anterior
        </a>
    </div>
</div>

{{-- WIZARD MONITOR --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body p-3 d-flex align-items-center">
        
        {{-- Sección de Pasos --}}
        <div class="flex-grow-1">
            <div class="d-flex justify-content-around text-center wizard-steps">
                
                {{-- Paso 1: Activo (Completado) --}}
                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.create') }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Activo</div>
                    </a>
                </div>

                {{-- Paso 2: Ubicación (Completado) --}}
                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.ubicacion', $uuid) }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Ubicación</div>
                    </a>
                </div>

                {{-- Paso 3: Monitor (Activo) --}}
                <div class="wizard-step active">
                    <a href="{{ route('equipos.wizard.monitor', $uuid) }}" class="text-primary font-weight-bold" style="text-decoration: none;">
                        <i class="fas fa-tv"></i>
                        <div class="small">Monitor</div>
                    </a>
                </div>

                {{-- Paso 4: Final (Pendiente) --}}
                <div class="wizard-step text-muted">
                    <i class="fas fa-flag-checkered"></i>
                    <div class="small">Final</div>
                </div>

            </div>
        </div>

        {{-- Separador Vertical --}}
        <div class="mx-3 d-none d-md-block" style="border-left: 1px solid #e0e0e0; height: 45px;"></div>

        {{-- Contenedor del Logo --}}
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
<div class="card card-outline card-success">
    <div class="card-body">

        <form action="{{ route('equipos.wizard.saveMonitor', $uuid) }}" method="POST" id="monitorForm">
            @csrf

            <fieldset class="fieldset-group">

                <legend class="mb-3">
                    <i class="fas fa-tv"></i> Datos del Monitor
                </legend>

                {{-- Silueta --}}
                <div class="text-center mb-4 text-muted">
                    <i class="fas fa-desktop fa-3x"></i>
                    <div class="small mt-1">Monitor asociado</div>
                </div>

                <div class="row">
                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="marca_select"><i class="fas fa-tag"></i> Marca</label>
                            <select id="marca_select" class="form-control">
                                <option value="" selected>Seleccione la marca</option>

                                <optgroup label="Cómputo">
                                    <option value="Dell">Dell</option>
                                    <option value="HP">HP</option>
                                    <option value="Lenovo">Lenovo</option>
                                    <option value="Samsung">Samsung</option>
                                    <option value="LG">LG</option>
                                    <option value="MSI">MSI</option>
                                    <option value="Gigabyte">Gigabyte</option>
                                    <option value="Huawei">Huawei</option>
                                    <option value="Xiaomi">Xiaomi</option>
                                </optgroup>

                                <optgroup label="Especializadas / Monitores">
                                    <option value="ASUS">ASUS</option>
                                    <option value="Acer">Acer</option>
                                    <option value="BenQ">BenQ</option>
                                    <option value="ViewSonic">ViewSonic</option>
                                    <option value="Philips">Philips</option>
                                    <option value="AOC">AOC</option>
                                    <option value="Eizo">Eizo</option>
                                </optgroup>

                                <optgroup label="Electrónica">
                                    <option value="Sony">Sony</option>
                                    <option value="Panasonic">Panasonic</option>
                                    <option value="Manhattan">Manhattan</option>
                                </optgroup>

                                <optgroup label="Otros">
                                    <option value="Otro">Otro</option>
                                </optgroup>
                            </select>                            
                            {{-- Input real para la marca --}}
                            <input type="text" name="marca" id="marca_input" 
                                   class="form-control custom-input" 
                                   placeholder="Escriba la marca aquí..."
                                   value="{{ old('marca', session('wizard_equipo.monitor.marca')) }}">
                            @error('marca') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="serial">
                                <i class="fas fa-barcode"></i> Serial
                            </label>
                            <input type="text"
                                   id="serial"
                                   name="serial"
                                   class="form-control"
                                   value="{{ old('serial', session('wizard_equipo.monitor.serial')) }}"
                                   placeholder="Ej. ABC12345">
                            @error('serial') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="pulgadas_select"><i class="fas fa-ruler-combined"></i> Tamaño (pulgadas)</label>
                            <select id="pulgadas_select" class="form-control">
                                <option value="">Seleccione tamaño</option>
                                <option value="14">14"</option>
                                <option value="15">15"</option>
                                <option value="17">17"</option>
                                <option value="18.5">18.5"</option>
                                <option value="19">19"</option>
                                <option value="20">20"</option>
                                <option value="21">21"</option>
                                <option value="22">22"</option>
                                <option value="23">23"</option>
                                <option value="24">24"</option>
                                <option value="25">25"</option>
                                <option value="27">27"</option>
                                <option value="28">28"</option>
                                <option value="29">29"</option>
                                <option value="31.5">31.5"</option>
                                <option value="32">32"</option>
                                <option value="34">34"</option>
                                <option value="38">38"</option>
                                <option value="40">40"</option>

                                <option value="OTRO_VALOR">-- Otro tamaño (Escribir) --</option>
                            </select>

                            
                            {{-- Input real para pulgadas --}}
                            <input type="text" name="escala_pulgadas" id="pulgadas_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej: 15.6 o 32"
                                   value="{{ old('escala_pulgadas', session('wizard_equipo.monitor.escala_pulgadas')) }}">
                            @error('escala_pulgadas') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="interface">
                                <i class="fas fa-plug"></i> Interfaz
                            </label>
                            <select name="interface" id="interface" class="form-control">
                                <option value="">Seleccione interfaz</option>

                                @php
                                    $interfaces = [
                                        'Integrado','HDMI','HDMI 1.4','HDMI 2.0','HDMI 2.1',
                                        'VGA',
                                        'DisplayPort (DP)',
                                        'Mini DisplayPort',
                                        'DVI','DVI-D','DVI-I',
                                        'USB-C (Display)',
                                        'Thunderbolt'
                                    ];
                                @endphp

                                @foreach($interfaces as $inter)
                                    <option value="{{ $inter }}"
                                        {{ old('interface', session('wizard_equipo.monitor.interface')) == $inter ? 'selected' : '' }}>
                                        {{ $inter }}
                                    </option>
                                @endforeach
                            </select>
                            @error('interface') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

            </fieldset>

            {{-- FOOTER --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('equipos.wizard.discoDuro', $uuid) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-fast-forward"></i> Omitir paso
                </a>

                <button type="submit" class="btn btn-success btn-lg px-5">
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
                if($input.val() === '') $input.val(''); 
            } else {
                $input.hide().val($(this).val()); 
            }
        });

        // Sincronización inicial
        let initialVal = $input.val();
        if(initialVal && !$select.find(`option[value='${initialVal}']`).length) {
            $select.val('OTRO_VALOR');
            $input.show();
        } else if (initialVal !== '') {
            $select.val(initialVal);
        }
    }

    setupSelectOtro('marca_select', 'marca_input');
    setupSelectOtro('pulgadas_select', 'pulgadas_input');
});
</script>
@stop
