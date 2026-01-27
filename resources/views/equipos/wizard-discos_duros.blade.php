@extends('adminlte::page')

@section('title', 'Wizard | Asignar Disco Duro')

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
                <i class="fas fa-hdd text-info"></i> Disco Duro
            </h1>
        </div>

        <a href="{{ route('equipos.wizard-monitores', $uuid) }}" class="btn btn-outline-secondary">
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

            <div class="wizard-step active">
            <a href="{{ route('equipos.wizard-discos_duros', $uuid) }}">
                <i class="fas fa-hdd"></i>
                <div>Disco Duro</div>
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

        <form action="{{ route('equipos.wizard.saveDiscoduro', $uuid) }}" method="POST" id="discoForm">
            @csrf

            <fieldset class="fieldset-group">

                <legend class="mb-3">
                    <i class="fas fa-hdd"></i> Datos del Almacenamiento
                </legend>

                {{-- Silueta --}}
                <div class="text-center mb-4 text-muted">
                    <i class="fas fa-database fa-3x"></i>
                    <div class="small mt-1">Disco asociado</div>
                </div>

                <div class="row">
                    {{-- COLUMNA IZQUIERDA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="capacidad_select"><i class="fas fa-archive"></i> Capacidad</label>
                            <select id="capacidad_select" class="form-control">
                                <option value="" selected>Seleccione capacidad</option>
                                <optgroup label="Unidades GB">
                                    <option value="120GB">120GB</option>
                                    <option value="240GB">240GB</option>
                                    <option value="256GB">256GB</option>
                                    <option value="480GB">480GB</option>
                                    <option value="500GB">500GB</option>
                                    <option value="512GB">512GB</option>
                                </optgroup>
                                <optgroup label="Unidades TB">
                                    <option value="1TB">1TB</option>
                                    <option value="2TB">2TB</option>
                                    <option value="4TB">4TB</option>
                                </optgroup>
                                <option value="OTRO_VALOR">-- Otra capacidad (Escribir) --</option>
                            </select>
                            
                            <input type="text" name="capacidad" id="capacidad_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. 128GB o 10TB"
                                   value="{{ old('capacidad', session('wizard_equipo.disco_duro.capacidad')) }}">
                            @error('capacidad') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="tipo_select"><i class="fas fa-memory"></i> Tipo de Disco</label>
                            <select id="tipo_select" class="form-control">
                                <option value="">Seleccione tipo</option>
                                <option value="SSD">SSD (Sólido)</option>
                                <option value="HDD">HDD (Mecánico)</option>
                                <option value="M.2 NVMe">M.2 NVMe (Alto rendimiento)</option>
                                <option value="M.2 SATA">M.2 SATA</option>
                                <option value="OTRO_VALOR">-- Otro tipo (Escribir) --</option>
                            </select>
                            
                            <input type="text" name="tipo_hdd_ssd" id="tipo_input" 
                                   class="form-control custom-input" 
                                   placeholder="Escriba el tipo de disco..."
                                   value="{{ old('tipo_hdd_ssd', session('wizard_equipo.disco_duro.tipo_hdd_ssd')) }}">
                            @error('tipo_hdd_ssd') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="interface_select"><i class="fas fa-plug"></i> Interfaz</label>
                            <select id="interface_select" class="form-control">
                                <option value="">Seleccione interfaz</option>
                                <option value="SATA III">SATA III</option>
                                <option value="PCIe Gen 3">PCIe Gen 3</option>
                                <option value="PCIe Gen 4">PCIe Gen 4</option>
                                <option value="SAS">SAS (Servidor)</option>
                                <option value="USB 3.0 / Externo">USB 3.0 / Externo</option>
                                <option value="OTRO_VALOR">-- Otra interfaz (Escribir) --</option>
                            </select>
                            
                            <input type="text" name="interface" id="interface_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. IDE, SCSI, etc."
                                   value="{{ old('interface', session('wizard_equipo.disco_duro.interface')) }}">
                            @error('interface') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

            </fieldset>

            {{-- FOOTER --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('equipos.wizard-ram', $uuid) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-fast-forward"></i> Omitir Almacenamiento
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
    /**
     * Función para sincronizar Selects con Inputs de "Otro"
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

        // Al cargar (por si hay errores de validación o datos en sesión)
        let initialVal = $input.val();
        if(initialVal && !$select.find(`option[value='${initialVal}']`).length && initialVal !== '') {
            $select.val('OTRO_VALOR');
            $input.show();
        } else if (initialVal !== '') {
            $select.val(initialVal);
        }
    }

    setupSelectOtro('capacidad_select', 'capacidad_input');
    setupSelectOtro('tipo_select', 'tipo_input');
    setupSelectOtro('interface_select', 'interface_input');
});
</script>
@stop