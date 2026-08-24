<div class="card card-outline card-info">
    <fieldset class="border p-3 mb-4">

        <legend class="w-auto px-2 text-primary">
            <i class="fas fa-link"></i> Asignación
        </legend>

        {{-- ============================================================
            FILA 1: USUARIO + UBICACIÓN
        ============================================================ --}}
        <div class="row">

            {{-- Usuario Responsable --}}
            <div class="form-group col-md-6">
                <label for="usuario_id">
                    <i class="fas fa-user-tag"></i> Usuario Responsable
                </label>

                <select
                    name="usuario_id"
                    id="usuario_id"
                    class="form-control select2 @error('usuario_id') is-invalid @enderror"
                    data-current="{{ $equipo->usuario_id }}"
                    data-placeholder="Seleccione un usuario"
                >
                    <option value="">Seleccione...</option>

                    @foreach($usuarios as $usuario)
                        <option
                            value="{{ $usuario->id }}"
                            @selected(
                                old('usuario_id', $equipo->usuario_id) == $usuario->id
                            )
                        >
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>

                @error('usuario_id')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Persona asignada para la custodia del activo.
                </small>
            </div>


            {{-- Ubicación --}}
            <div class="form-group col-md-6">
                <label for="ubicacion_id">
                    <i class="fas fa-map-marker-alt"></i> Ubicación
                </label>

                <select
                    name="ubicacion_id"
                    id="ubicacion_id"
                    class="form-control select2 @error('ubicacion_id') is-invalid @enderror"
                    data-current="{{ $equipo->ubicacion_id }}"
                    data-label="Ubicación"
                    data-placeholder="Seleccione una ubicación"
                    data-motivo-input="#motivo_cambio_ubicacion"
                >
                    <option value="">Seleccione...</option>

                    @foreach($ubicaciones as $ubicacion)
                        <option
                            value="{{ $ubicacion->id }}"
                            @selected(
                                old('ubicacion_id', $equipo->ubicacion_id) == $ubicacion->id
                            )
                        >
                            {{ $ubicacion->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('ubicacion_id')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Sede o ubicación física donde se encuentra el equipo.
                </small>

                <input
                    type="hidden"
                    name="motivo_cambio_ubicacion"
                    id="motivo_cambio_ubicacion"
                >
            </div>

        </div>


        {{-- ============================================================
            FILA 2: ÁREA + DEPARTAMENTO
        ============================================================ --}}
        <div class="row">

            {{-- Área --}}
            <div class="form-group col-md-6">
                <label for="empresa_id">
                    <i class="fas fa-sitemap"></i> Área
                </label>

                <select
                    name="empresa_id"
                    id="empresa_id"
                    class="form-control select2 @error('empresa_id') is-invalid @enderror"
                    data-current="{{ $equipo->empresa_id }}"
                    data-label="Área"
                    data-placeholder="Seleccione un área"
                >
                    <option value="">-- Sin área asignada --</option>

                    @foreach($empresas as $empresa)
                        <option
                            value="{{ $empresa->id }}"
                            @selected(
                                old('empresa_id', $equipo->empresa_id) == $empresa->id
                            )
                        >
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('empresa_id')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Área u organización a la que pertenece el activo.
                </small>
            </div>


            {{-- Departamento --}}
            <div class="form-group col-md-6">
                <label for="departamento_perteneciente">
                    <i class="fas fa-building"></i> Departamento
                </label>

                <select
                    name="departamento_perteneciente"
                    id="departamento_perteneciente"
                    class="form-control select2 @error('departamento_perteneciente') is-invalid @enderror"
                    data-current="{{ $equipo->departamento_perteneciente }}"
                    data-label="Departamento"
                    required
                >
                    <option
                        value=""
                        disabled
                        @selected(
                            old(
                                'departamento_perteneciente',
                                $equipo->departamento_perteneciente
                            ) == ''
                        )
                    >
                        -- Seleccione un departamento --
                    </option>

                    @foreach($departamentos as $dep)
                        <option
                            value="{{ $dep->nombre }}"
                            @selected(
                                old(
                                    'departamento_perteneciente',
                                    $equipo->departamento_perteneciente
                                ) == $dep->nombre
                            )
                        >
                            {{ $dep->nombre }}
                        </option>
                    @endforeach
                </select>

                @error('departamento_perteneciente')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Centro de costos responsable.
                </small>
            </div>

        </div>


        {{-- ============================================================
            FILA 3: VALOR INICIAL + FECHA DE ADQUISICIÓN
        ============================================================ --}}
        <div class="row">

            {{-- Valor Inicial --}}
            <div class="form-group col-md-6">
                <label for="valor_inicial">
                    <i class="fas fa-dollar-sign"></i> Valor Inicial
                </label>

                <input
                    type="number"
                    name="valor_inicial"
                    id="valor_inicial"
                    class="form-control @error('valor_inicial') is-invalid @enderror"
                    step="0.01"
                    data-current="{{ $equipo->valor_inicial }}"
                    data-motivo-input="#motivo_cambio_valor"
                    value="{{ old('valor_inicial', $equipo->valor_inicial) }}"
                >

                @error('valor_inicial')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Costo original de adquisición (sin IVA).
                </small>

                <input
                    type="hidden"
                    name="motivo_cambio_valor"
                    id="motivo_cambio_valor"
                >
            </div>


            {{-- Fecha de Adquisición --}}
            <div class="form-group col-md-6">
                <label for="fecha_adquisicion">
                    <i class="fas fa-calendar-alt"></i> Fecha de Adquisición
                </label>

                <input
                    type="date"
                    name="fecha_adquisicion"
                    id="fecha_adquisicion"
                    class="form-control @error('fecha_adquisicion') is-invalid @enderror"
                    value="{{ old(
                        'fecha_adquisicion',
                        $equipo->fecha_adquisicion?->format('Y-m-d')
                    ) }}"
                    data-current="{{ $equipo->fecha_adquisicion ? \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('Y-m-d') : '' }}"
                    data-motivo-input="#motivo_cambio_fecha"
                >

                @error('fecha_adquisicion')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Fecha según factura de compra.
                </small>

                <input
                    type="hidden"
                    name="motivo_cambio_fecha"
                    id="motivo_cambio_fecha"
                >
            </div>

        </div>


        {{-- ============================================================
            FILA 4: FECHA INICIO DE USO + VIDA ÚTIL
        ============================================================ --}}
        <div class="row">

            {{-- Fecha Inicio de Uso --}}
            <div class="form-group col-md-6">
                <label for="fecha_inicio_uso">
                    <i class="fas fa-play-circle"></i>
                    Fecha Inicio de Uso (Fiscal)
                </label>

                <input
                    type="date"
                    name="fecha_inicio_uso"
                    id="fecha_inicio_uso"
                    class="form-control border-success @error('fecha_inicio_uso') is-invalid @enderror"
  value="{{ old(
    'fecha_inicio_uso', 
    isset($equipo->fecha_inicio_uso) ? \Carbon\Carbon::parse($equipo->fecha_inicio_uso)->format('Y-m-d') : ''
) }}"
data-current="{{ isset($equipo->fecha_inicio_uso) ? \Carbon\Carbon::parse($equipo->fecha_inicio_uso)->format('Y-m-d') : '' }}"                    data-label="Fecha Inicio de Uso"
                    data-motivo-input="#motivo_cambio_inicio_uso"
                >
                @error('fecha_inicio_uso')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror

                <small class="text-muted">
                    Fecha en la que el activo comenzó a generar beneficios.
                </small>

                <input
                    type="hidden"
                    name="motivo_cambio_inicio_uso"fecha_inicio_uso
                    id="motivo_cambio_inicio_uso"
                >
            </div>


                <input
                    type="date"
                    name="fecha_adquisicion"
                    id="fecha_adquisicion"
                    class="form-control @error('fecha_adquisicion') is-invalid @enderror"
                    value="{{ old(
                        'fecha_adquisicion',
                        $equipo->fecha_adquisicion?->format('Y-m-d')
                    ) }}"
                    data-current="{{ $equipo->fecha_adquisicion?->format('Y-m-d') }}"
                    data-motivo-input="#motivo_cambio_fecha"
                >


            {{-- Vida Útil Estimada --}}
            <div class="form-group col-md-6">
                <label for="vida_util_input">
                    <i class="fas fa-hourglass-half"></i>
                    Vida Útil Estimada
                </label>

                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light">
                            Años
                        </span>
                    </div>

                    <input
                        type="number"
                        name="vida_util_estimada"
                        id="vida_util_input"
                        class="form-control @error('vida_util_estimada') is-invalid @enderror"
                        placeholder="Ej. 5"
                        min="1"
                        max="100"
                        value="{{ old(
                            'vida_util_estimada',
                            $equipo->vida_util_estimada
                        ) }}"
                        data-current="{{ $equipo->vida_util_estimada }}"
                        data-label="Vida Útil Estimada"
                        data-motivo-input="#motivo_cambio_vidaEstimada"
                        required
                    >

                    @error('vida_util_estimada')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <small class="text-muted">
                    Cantidad de años para el cálculo de depreciación.
                </small>

                <input
                    type="hidden"
                    name="motivo_cambio_vidaEstimada"
                    id="motivo_cambio_vidaEstimada"
                >
            </div>

        </div>

    </fieldset>
</div>