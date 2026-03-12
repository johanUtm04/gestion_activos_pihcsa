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
                <i class="fas fa-microchip text-danger"></i> Procesador (CPU)
            </h1>
        </div>

        <a href="{{ route('equipos.wizard.ram', $uuid) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left"></i> Anterior
        </a>
    </div>
</div>

{{-- WIZARD SIMULACION (MANTENIENDO TUS MIGAJAS) --}}
<div class="card mb-3 shadow-sm">
    <div class="card-body p-3 d-flex align-items-center">
        <div class="flex-grow-1">
            <div class="d-flex justify-content-around text-center wizard-steps">
                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.create') }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Activo</div>
                    </a>
                </div>

                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.ubicacion', $uuid) }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Ubicación</div>
                    </a>
                </div>

                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.monitor', $uuid) }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Monitor</div>
                    </a>
                </div>

                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.discoDuro', $uuid) }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Disco Duro</div>
                    </a>
                </div>

                <div class="wizard-step completed">
                    <a href="{{ route('equipos.wizard.ram', $uuid) }}" class="text-success" style="text-decoration: none;">
                        <i class="fas fa-check-circle"></i>
                        <div class="small">Ram</div>
                    </a>
                </div>

                <div class="wizard-step active">
                    <a href="{{ route('equipos.wizard.procesador', $uuid) }}" class="text-primary font-weight-bold" style="text-decoration: none;">
                        <i class="fas fa-microchip"></i>
                        <div class="small">Procesador</div>
                    </a>
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

<div class="card card-outline card-danger">
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
                                <option value="Apple">Apple</option>
                                <option value="Qualcomm">Qualcomm</option>
                                <option value="MediaTek">MediaTek</option>
                                <option value="IBM">IBM</option>
                                <option value="NVIDIA">NVIDIA</option>
                                <option value="VIA">VIA</option>

                                <option value="Dell">Dell</option>
                                <option value="HP">HP</option>
                                <option value="Lenovo">Lenovo</option>
                                <option value="ASUS">ASUS</option>
                                <option value="Acer">Acer</option>
                                <option value="Samsung">Samsung</option>
                                <option value="LG">LG</option>
                                <option value="Microsoft">Microsoft</option>
                                <option value="Huawei">Huawei</option>
                                <option value="MSI">MSI</option>
                                <option value="Gigabyte">Gigabyte</option>

                                <option value="Otro">Otro</option>
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
                                    <option value="Celeron">Celeron</option>
                                    <option value="Pentium">Pentium</option>
                                    <option value="Xeon">Xeon</option>
                                </optgroup>

                                <optgroup label="AMD">
                                    <option value="Ryzen 3">Ryzen 3</option>
                                    <option value="Ryzen 5">Ryzen 5</option>
                                    <option value="Ryzen 7">Ryzen 7</option>
                                    <option value="Ryzen 9">Ryzen 9</option>
                                    <option value="Threadripper">Threadripper</option>
                                    <option value="Athlon">Athlon</option>
                                    <option value="EPYC">EPYC</option>
                                </optgroup>

                                <optgroup label="Apple">
                                    <option value="M1">M1</option>
                                    <option value="M2">M2</option>
                                    <option value="M3">M3</option>
                                </optgroup>

                                <optgroup label="ARM / Otros">
                                    <option value="Snapdragon">Snapdragon</option>
                                    <option value="MediaTek">MediaTek</option>
                                    <option value="Otro">Otro</option>
                                </optgroup>
                            </select>

                            <input type="text" name="descripcion_tipo" id="desc_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. Core i7-11700K"
                                   value="{{ old('descripcion_tipo', session('wizard_equipo.procesador.descripcion_tipo')) }}">
                            @error('descripcion_tipo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>


                {{-- Frecuencia (NUEVO CAMPO) --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="frec_select"><i class="fas fa-tachometer-alt"></i> Frecuencia (GHz)</label>
                        
                        @php
                            // Generación de lista completa
                            $rangoFrec = [];
                            for ($i = 0.9; $i <= 5.0; $i += 0.1) { $rangoFrec[] = number_format($i, 2); }
                            $especialesFrec = ['1.30', '1.70', '2.42', '2.59', '3.19', '3.33'];
                            $frecuenciasFinales = collect($rangoFrec)->merge($especialesFrec)->unique()->sort()->values();
                            
                            // Detectar valor actual en la sesión del Wizard
                            $valorSesion = old('clock_ghz', session('wizard_equipo.procesador.clock_ghz'));
                            $valorFormateado = $valorSesion ? number_format((float)$valorSesion, 2) : null;
                            $esOtroFrec = $valorSesion && !$frecuenciasFinales->contains($valorFormateado);
                        @endphp

                        <select id="frec_select" class="form-control">
                            <option value="">Seleccione velocidad</option>
                            @foreach($frecuenciasFinales as $frec)
                                <option value="{{ $frec }}" {{ ($valorFormateado == $frec) ? 'selected' : '' }}>
                                    {{ $frec }} GHz
                                </option>
                            @endforeach
                            <option value="OTRO_VALOR" {{ $esOtroFrec ? 'selected' : '' }}>[ Otro valor ]</option>
                        </select>

                        {{-- Input real que se envía al servidor --}}
                        <input type="number" step="0.01" name="clock_ghz" id="frec_input" 
                            class="form-control mt-2 {{ $esOtroFrec ? '' : 'custom-input' }}" 
                            placeholder="Ej. 3.45" 
                            value="{{ $valorSesion }}">
                        
                        @error('clock_ghz') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>


                </div>

            </fieldset>

            {{-- FOOTER FINAL --}}

            {{-- FOOTER --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('equipos.wizard.periferico', $uuid) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-fast-forward"></i> Omitir Procesador
                </a>

                <button type="submit" class="btn btn-danger btn-lg px-5">
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
     * @param selectId ID del selector visual
     * @param inputId  ID del input real que se guarda
     */
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);

        $select.on('change', function() {
            const val = $(this).val();
            if (val === 'OTRO_VALOR') {
                $input.removeClass('custom-input').hide().fadeIn().focus();
                // No borramos el valor inmediatamente para permitir correcciones
            } else {
                $input.fadeOut(function() {
                    $(this).addClass('custom-input').val(val);
                });
            }
        });

        // Lógica de carga inicial (Session/Old Data)
        let initialVal = $input.val();
        if (initialVal !== '') {
            // Intentar encontrar el valor en las opciones del select
            // Usamos parseFloat para comparar números como 2.4 y 2.40 correctamente
            let matchingOption = $select.find('option').filter(function() {
                return parseFloat($(this).val()) === parseFloat(initialVal);
            });

            if (matchingOption.length > 0) {
                $select.val(matchingOption.val());
                $input.addClass('custom-input').hide();
            } else {
                $select.val('OTRO_VALOR');
                $input.removeClass('custom-input').show();
            }
        }
    }

    // Inicializar los tres campos del Wizard
    setupSelectOtro('marca_select', 'marca_input');
    setupSelectOtro('desc_select', 'desc_input');
    setupSelectOtro('frec_select', 'frec_input');
});
</script>
@stop