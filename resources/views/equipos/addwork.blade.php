@extends('adminlte::page')

@section('title', 'Mantenimiento de Activo')

@section('css')
<style>
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

    .form-group label {
        font-weight: 600;
    }

    .custom-input { display: none; margin-top: 10px; }
</style>
@stop

@section('content_header')
<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center">

    <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver al inventario
    </a>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-tools"></i>
                        Mantenimiento de {{ $equipo->tipo_equipo ?? '[Activo]' }} | Marca: {{ $equipo->marca_equipo ?? '[Activo]' }}
                    </h3>
                </div>

                <form method="POST" action="{{ route('equipos.addwork.store', $equipo) }}">
                    @csrf

                    <div class="card-body">
                        <fieldset class="fieldset-group">
                            <legend>
                                <i class="fas fa-clipboard-list"></i>
                                Detalle del evento
                            </legend>

                            <div class="form-group">
                                <label>Tipo de evento </label>
                                <select class="form-control" id="tipo_evento" name="tipo_evento" required>
                                    <option value="">Seleccione una opción</option>
                                    <option>Mantenimiento preventivo</option>
                                    <option>Mantenimiento correctivo</option>
                                    <option>Actualización</option>
                                    <option value="OTRO_VALOR">-- Otra capacidad (Escribir) --</option>
                                </select>
                                    <input type="text" name="tipo_evento_input" id="tipo_evento_input" 
                                   class="form-control custom-input" 
                                   placeholder="Ej. 128GB o 10TB"
                                   value="{{ old('capacidad', session('wizard_equipo.disco_duro.capacidad')) }}">
                            </div>

                    <div class="form-group">
                        <label>Usuario que realizo la Accion</label>
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


                            <div class="form-group">
                                <label>Fecha del evento </label>
                                <input type="date" class="form-control" name="fecha_evento" required>
                            </div>

                            <div class="form-group">
                                <label>Contexto del evento </label>
                                <textarea class="form-control" rows="4" name="contexto"
                                    placeholder="Descripción del mantenimiento..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Costo (opcional)</label>
                                <input type="number" step="0.01" class="form-control"
                                    name="costo" placeholder="$0.00">
                            </div>
                        </fieldset>

                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Registrar mantenimiento
                        </button>

                        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>

        </div>
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

    setupSelectOtro('tipo_evento', 'tipo_evento_input');
});
</script>
@stop

