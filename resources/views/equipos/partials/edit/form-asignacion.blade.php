<div class="card card-outline card-info">
<fieldset class="border p-3 mb-4">
    <legend class="w-auto px-2 text-primary"><i class="fas fa-link"></i> Asignacion</legend>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="usuario_id"><i class="fas fa-user-tag"></i> Usuario Responsable</label>
            <select name="usuario_id" id="usuario_id" class="form-control" 
                data-placeholder="Seleccione un usuario">
                <option value="">Seleccione...</option>
                @foreach($usuarios as $usuario) 
                <option value="{{ $usuario->id }}"
                    {{ $equipo->usuario_id == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="ubicacion_id"><i class="fas fa-map-marker-alt"></i> Ubicacion</label>
            <select name="ubicacion_id" id="ubicacion_id" class="form-control select2 " 
                data-current="{{ $equipo->ubicacion_id }}"
                    data-label=" la ubicacion actual"
                        data-placeholder="Seleccione una ubicacion"
                             data-motivo-input="#motivo_cambio_ubicacion">
                <option value="">Seleccione...</option>
                @foreach($ubicaciones as $ubicacion)
                <option value="{{ $ubicacion->id }}"
                    {{ $equipo->ubicacion_id == $ubicacion->id ? 'selected' : '' }}>
                    {{ $ubicacion->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="" id="motivo_cambio_ubicacion">
    </div>

    <div class="row">

        <div class="form-group col-md-4">
            <label for="departamento_perteneciente">
                <i class="fas fa-building"></i> Departamento
            </label>

            <select name="departamento_perteneciente" 
                    id="departamento_perteneciente"
                    class="form-control select2 @error('departamento_perteneciente') is-invalid @enderror" 
                    required>
                
                <option value="" disabled {{ old('departamento_perteneciente', $equipo->departamento_perteneciente) == '' ? 'selected' : '' }}>
                    -- Seleccione un departamento --
                </option>

                @php
                    $deps = [
                        'ADMINISTRACION'    => 'ADMINISTRACION',
                        'ALMACEN'           => 'ALMACEN',
                        'CALIDAD'           => 'CALIDAD',
                        'COBRANZA'          => 'COBRANZA',
                        'COMPRAS'           => 'COMPRAS',
                        'CONTABILIDAD'      => 'CONTABILIDAD',
                        'CREDITO'           => 'CREDITO',
                        'CULTURA Y TALENTO' => 'CULTURA Y TALENTO',
                        'DIRECCION'         => 'DIRECCION',       
                        'EMBARQUES'         => 'EMBARQUES',
                        'INVENTARIOS'       => 'INVENTARIOS',     
                        'JURIDICO'          => 'JURIDICO',
                        'LOGISTICA'         => 'LOGISTICA',      
                        'SISTEMAS'          => 'SISTEMAS',
                        'VENTAS'            => 'VENTAS',
                    ];
                @endphp

                @foreach($deps as $val => $label)
                    <option value="{{ $val }}" {{ old('departamento_perteneciente', $equipo->departamento_perteneciente) == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            @error('departamento_perteneciente')
                <span class="invalid-feedback" role="alert">
                    <strong>El departamento es obligatorio.</strong>
                </span>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="valor_inicial"><i class="fas fa-dollar-sign"></i> Valor Inicial</label>
            <input type="number" name="valor_inicial" id="valor_inicial" class="form-control "
                step="0.01"
                data-current="{{ $equipo->valor_inicial }}"
                    data-label=" la valor inicial"
                        data-placeholder="Seleccione un valor"
                             data-motivo-input="#motivo_cambio_valor"
                                value="{{ old('valor_inicial', $equipo->valor_inicial) }}">
            <input type="hidden" name="" id="motivo_cambio_valor">
        </div>

        <div class="form-group col-md-4">
            <label for="fecha_adquisicion"><i class="fas fa-calendar-alt"></i> Fecha de Adquisicion</label>
            <input type="date" 
                name="fecha_adquisicion" 
                id="fecha_adquisicion" 
                class="form-control "
                value="{{ old('fecha_adquisicion', $equipo->fecha_adquisicion ? $equipo->fecha_adquisicion->format('Y-m-d') : '') }}"
                data-current="{{ $equipo->fecha_adquisicion }}"
                    data-label=" la fecha de adquisicion actual"
                        data-placeholder="Seleccione la fecha de adquisicion"
                             data-motivo-input="#motivo_cambio_fecha">
                <input type="hidden" name="" id="motivo_cambio_fecha">
        </div>

        <div class="form-group col-md-6">
            <label for="vida_util_input"><i class="fas fa-hourglass-half"></i> Vida Útil Estimada</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light">Años</span>
                </div>
                
                <input 
                    type="number" 
                    name="vida_util_estimada" 
                    id="vida_util_input"
                    class="form-control form-componentes" 
                    placeholder="Ej. 5"
                    min="1"
                    max="100"
                    value="{{ old('vida_util_estimada', $equipo->vida_util_estimada) }}" 
                    data-current="{{ $equipo->vida_util_estimada }}"
                    data-label="la vida útil estimada"
                    data-motivo-input="#motivo_cambio_vidaEstimada"
                    required>
                
                <input type="hidden" name="motivo_cambio_vida_util" id="motivo_cambio_vidaEstimada">
            </div>
            <small class="text-muted">Ingrese la cantidad de años para depreciación.</small>
        </div>
    </div>
</fieldset>
</div>