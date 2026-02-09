<div class="card card-outline card-info">
<fieldset class="border p-3 mb-4">
    <legend class="w-auto px-2 text-primary"><i class="fas fa-link"></i> Asignacion</legend>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="usuario_id"><i class="fas fa-user-tag"></i> Usuario Responsable</label>
            <select name="usuario_id" id="usuario_id" class="form-control select2" 
                data-current="{{ $equipo->usuario_id }}"
                data-label="el usuario responsable"
                data-placeholder="Seleccione un usuario">
                <option value="">Seleccione...</option>
                @foreach($usuarios as $usuario) 
                <option value="{{ $usuario->id }}"
                    {{ $equipo->usuario_id == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->name }}
                </option>
                @endforeach
            </select>
            <input type="hidden" name="motivo_cambio_usuario" id="motivo_cambio_usuario">
        </div>

        <div class="form-group col-md-6">
            <label for="ubicacion_id"><i class="fas fa-map-marker-alt"></i> Ubicacion</label>
            <select name="ubicacion_id" id="ubicacion_id" class="form-control select2" 
            data-current="{{ $equipo->ubicacion_id }}"
            data-label="la ubicacion del activo"
            data-placeholder="Seleccione la ubicacion">
                <option value="">Seleccione...</option>
                @foreach($ubicaciones as $ubicacion)
                <option value="{{ $ubicacion->id }}"
                    {{ $equipo->ubicacion_id == $ubicacion->id ? 'selected' : '' }}>
                    {{ $ubicacion->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="motivo_cambio_ubicacion" id="motivo_cambio_ubicacion">
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="valor_inicial"><i class="fas fa-dollar-sign"></i> Valor Inicial</label>
            <input type="number" name="valor_inicial" id="valor_inicial" class="form-control"
                step="0.01"
                data-current="{{ $equipo->valor_inicial }}"
                data-label="la Fecha de Adquisición"
                value="{{ old('valor_inicial', $equipo->valor_inicial) }}">
            <input type="hidden" name="motivo_cambio_valor" id="motivo_cambio_valor">
        </div>

        <div class="form-group col-md-4">
            <label for="fecha_adquisicion"><i class="fas fa-calendar-alt"></i> Fecha de Adquisicion</label>
            <input type="date" 
                name="fecha_adquisicion" 
                id="fecha_adquisicion" 
                class="form-control"
                value="{{ old('fecha_adquisicion', $equipo->fecha_adquisicion ? $equipo->fecha_adquisicion->format('Y-m-d') : '') }}"
                data-current="{{ $equipo->fecha_adquisicion ? $equipo->fecha_adquisicion->format('Y-m-d') : '' }}" 
                data-motivo-input="#motivo_cambio_fecha" 
                data-label="la Fecha de Adquisición">
                <input type="hidden" name="motivo_cambio_fecha" id="motivo_cambio_fecha">
        </div>

        <div class="form-group col-md-6">
            <label for="vida_util_estimada"><i class="fas fa-hourglass-half"></i> Vida Util Estimada</label>
            <div class="input-group">
                <select class="form-control" name="vida_util_unidad" id="vida_util_unidad"
                >
                    @php
                        $unidadActual = old('vida_util_unidad', $equipo->vida_util_unidad ?? '');
                    @endphp
                    <option value="" disabled {{ $unidadActual == '' ? 'selected' : '' }}>Unidad</option>
                    <option value="años" {{ $unidadActual == 'años' ? 'selected' : '' }}>Años</option>
                    <option value="meses" {{ $unidadActual == 'meses' ? 'selected' : '' }}>Meses</option>
                </select>

                <input 
                    type="number" 
                    name="vida_util_estimada" 
                    id="vida_util_input"
                    class="form-control" 
                    style="width: 50%;"
                    placeholder="Cantidad"
                    min="1"
                    value="{{ old('vida_util_estimada', $equipo->vida_util_estimada) }}" 
                    {{ $unidadActual ? '' : 'disabled' }}
                    >
            </div>
        </div>
    </div>
</fieldset>
</div>