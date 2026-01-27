@extends('adminlte::page')

@section('title', 'Registrar Nuevo Activo TI')

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

    .fieldset-group {
        border: 1px solid #ced4da;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: .25rem;
        background-color: #ffffff;
    }

    .fieldset-group legend {
        width: inherit;
        padding: 0 10px;
        border-bottom: none;
        font-size: 1.1em;
        font-weight: 600;
        color: #007bff;
    }

    .fieldset-group i.fa-3x {
        opacity: 0.25;
    }

    .form-group label {
        font-weight: 500;
    }

    .custom-input {
    display: none;
    margin-top: 10px;
    }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <h1 class="font-weight-bold mb-1">
        <i class="fas fa-plus-circle text-success"></i> Registrar Nuevo Activo
    </h1>
    <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver al inventario
    </a>
</div>

{{-- WIZARD --}}
<div class="card mb-3">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between text-center wizard-steps">
            <div class="wizard-step active">
                <i class="fas fa-desktop"></i>
                <div>Activo</div>
            </div>
            <div class="wizard-step">
                <i class="fas fa-map-marker-alt"></i>
                <div>Ubicacion</div>
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

{{-- ERRORES --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <strong><i class="fas fa-exclamation-triangle"></i> Revisa los datos</strong>
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('equipos.store') }}" method="POST">
@csrf

<div class="card card-outline card-success">
    <div class="card-body">

        <div class="row">

            {{-- COLUMNA IZQUIERDA --}}
            <div class="col-md-6">
                <fieldset class="fieldset-group">
                    <legend><i class="fas fa-info-circle"></i> Base del Activo</legend>

                    {{-- Silueta --}}
                    <div class="text-center mb-3 text-muted">
                        <i class="fas fa-laptop fa-3x"></i>
                        <div class="small mt-1">Informacion del equipo</div>
                    </div>

                    <div class="form-group">
                        <label for="marca_id"> Marca:</label>
                        <select name="marca_id" id="marca_id" class="form-control select2" required>
                            <option value="">Seleccione marca (o agregue desde catalogo) </option>
                            @foreach(\App\Models\Marca::all() as $marca)
                            <option value="{{ $marca->id }}" {{ old('marca_id') == $marca->id ? 'selected' : '' }}>
                            {{ strtoupper($marca->nombre) }}
                            </option>
                        @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipo_activo_id"> Tipo de Activo:</label>
                        <select name="tipo_activo_id" id="tipo_activo_id" class="form-control select2" required>
                            <option value="">Seleccione el tipo de equipo (o agregue desde catalogo)</option>
                            @foreach(\App\Models\TipoActivo::all() as $tipo)
                                <option value="{{ $tipo->id }}" {{ old('tipo_activo_id') == $tipo->id ? 'selected' : '' }}>
                                    {{ strtoupper($tipo->nombre) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Serial</label>
                        <input type="text" name="serial" class="form-control"
                               placeholder="Número de serie"
                               value="{{ old('serial', $equipo['serial'] ?? '') }}">
                        <small class="form-text text-muted">
                            Identificador único del activo
                        </small>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-5">
                            <label>SO</label>
                            <select id="so_familia" class="form-control" onchange="combinarSO()">
                                <option value="Windows">Windows</option>
                                <option value="Linux">Linux</option>
                                <option value="macOS">macOS</option>
                                <option value="Android">Android</option>
                            </select>
                        </div>

                        <div class="form-group col-md-5">
                            <label>Versión</label>
                            <input type="text" id="so_version" class="form-control" placeholder="Ej: 11 Pro / Ubuntu 22.04" oninput="combinarSO()">
                        </div>

                        <input type="hidden" name="sistema_operativo" id="so_final" value="{{ old('sistema_operativo', $equipo->sistema_operativo ?? '') }}">
                    </div>

                <script>
function combinarSO() {
    const familia = document.getElementById('so_familia').value;
    const version = document.getElementById('so_version').value;
    
    // Une ambos valores con el pipe |
    document.getElementById('so_final').value = familia + "|" + version;
}
</script>

                </fieldset>
            </div>

            {{-- COLUMNA DERECHA --}}
            <div class="col-md-6">
                <fieldset class="fieldset-group">
                    <legend><i class="fas fa-clipboard-check"></i> Asignación y Valor</legend>

                    {{-- Silueta --}}
                    <div class="text-center mb-3 text-muted">
                        <i class="fas fa-user-cog fa-3x"></i>
                        <div class="small mt-1">Responsable y valor</div>
                    </div>

                    <div class="form-group">
                        <label>Usuario responsable </label>
                        <select name="usuario_id" class="form-control select2" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Valor inicial </label>
                        <input type="number" name="valor_inicial" class="form-control"
                        step="0.01" placeholder="15000.00"
                        value="{{ old('valor_inicial', $equipo['valor_inicial'] ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Fecha de adquisición </label>
                        <input type="date" name="fecha_adquisicion" class="form-control"
                               value="{{ old('fecha_adquisicion', $equipo['fecha_adquisicion'] ?? '') }}" required>
                    </div>

                    <!-- Input con select asociado -->
                    <div class="form-group">
                        <label>Vida útil estimada </label>
                            <div class="input-group">
                            <select class="form-control" name="vida_util_unidad" required>
                                <option value="" disabled selected>Seleccione unidad</option>
                                <option value="años">Años</option>
                                <option value="meses">Meses</option>
                            </select>
                        </div>
                        <input 
                        type="number"
                        name="vida_util_estimada" 
                        class="form-control"
                        placeholder="Cantidad"
                        value="{{ old('vida_util_estimada', $equipo['vida_util_estimada'] ?? '') }}" 
                         disabled
                        required>
                    </div>
                </fieldset>
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="fas fa-arrow-right"></i> Continuar
        </button>
        
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-lg">
            Cancelar
        </a>
    </div>
</div>
</form>

@stop


@section('js')
<script>
    //Tomamos el nombre del select y del input para poder hacer operaciones con ellos 
    const unidad = document.querySelector('[name="vida_util_unidad"]');
    const valor = document.querySelector('[name="vida_util_estimada"]');

    //Evento que se ejeucta cada que el usuario cambia la opcion de Select 
    unidad.addEventListener('change', () => {
        valor.disabled =false;
        valor.placeholder = unidad.value === 'años'
        ? 'Ej. 5' : 'Ej. 60';
    });

    //1.-JavaScript para input dinamico
    $(document).ready(function() {
    function setupSelectOtro(selectId, inputId) {
        const $select = $(`#${selectId}`);
        const $input = $(`#${inputId}`);

        //Si se nota un cambio en la etiqueta <select>
        $select.on('change', function() {


            if ($(this).val() === 'OTRO_VALOR') {
                $input
                .val('')  
                .fadeIn().
                focus();
            } else {
                $input
                .hide()
                .val(''); 
            }
        });
    }
    //Select | Input Oculto
    setupSelectOtro('marca_equipo', 'marca_equipo_input');
    setupSelectOtro('tipo_equipo', 'tipo_equipo_input');

});

</script>

<script>
function actualizarSO() {
    const familia = document.getElementById('so_familia').value;
    const inputVersion = document.getElementById('sistema_operativo');
    
    if (familia !== 'Otro') {
        // Pre-rellena el inicio del input para ayudar al usuario
        inputVersion.value = familia + " ";
        inputVersion.focus();
    } else {
        inputVersion.value = "";
        inputVersion.placeholder = "Especifique sistema y versión";
        inputVersion.focus();
    }
}

</script>


@stop