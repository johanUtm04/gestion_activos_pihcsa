@extends('adminlte::page')

@section('title', 'Editar Vehículo')

@section('css')
<style>
    /* --- Estructura Dual Adaptativa --- */
    .inventory-wrapper {
        display: flex;
        gap: 20px;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        align-items: flex-start;
    }

    .form-container {
        flex: 1;
        transition: all 0.4s ease;
        min-width: 0;
    }

    /* --- Panel Lateral Estilo Intel Preview --- */
    .preview-sidebar {
        width: 380px;
        position: sticky;
        top: 20px;
        transition: opacity 0.35s ease, transform 0.35s ease;
    }

    /* --- Tipografía y Datos Secundarios --- */
    .asset-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #212529;
    }

    .secondary-data {
        display: block;
        font-size: 0.78rem;
        color: #6c757d;
        margin-top: 3px;
    }

    .secondary-data i {
        width: 14px;
        text-align: center;
        color: #17a2b8;
    }

    /* --- Badges Estilizados de Mantenimiento --- */
    .badge-status {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 6px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: inline-block;
        min-width: 85px;
    }

    .badge-status-rojo {
        background-color: #fde8e8;
        color: #e02424;
        border: 1px solid #f8b4b4;
    }

    .badge-status-amarillo {
        background-color: #fef08a;
        color: #854d0e;
        border: 1px solid #fef08a;
    }

    .badge-status-verde {
        background-color: #def7ec;
        color: #03543f;
        border: 1px solid #bcf0da;
    }

    /* --- Formulario Premium --- */
    .form-section-title {
        color: #17a2b8;
        font-weight: 800;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 8px;
        margin-bottom: 16px;
    }

    .form-control,
    .custom-select {
        border-radius: 8px;
        font-size: 0.86rem;
        border-color: #dbe3ea;
    }

    .form-control:focus,
    .custom-select:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.15rem rgba(23, 162, 184, 0.18);
    }

    label {
        font-size: 0.72rem;
        font-weight: 800;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        margin-bottom: 5px;
    }

    .required-mark {
        color: #dc3545;
    }

    .input-error {
        display: block;
        margin-top: 4px;
        font-size: 0.72rem;
        color: #dc3545;
        font-weight: 700;
    }

    .status-box {
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 14px;
    }

    /* --- Medidores de Diagnóstico del Panel --- */
    .metric-progress {
        height: 6px;
        border-radius: 3px;
        background-color: #e9ecef;
        margin-top: 4px;
    }

    .metric-progress-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease-in-out;
    }

    /* --- Footer / Botones --- */
    .btn-premium {
        border-radius: 8px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .edit-summary-card {
        border-radius: 12px;
    }

    .readonly-pill {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #343a40;
    }

    @media (max-width: 991px) {
        .inventory-wrapper {
            display: block;
        }

        .preview-sidebar {
            width: 100%;
            position: relative;
            top: 0;
            margin-top: 20px;
        }
    }
</style>
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.6rem;">
                Editar Vehículo
            </h1>
            <small class="text-muted">
                Activo:
                <span class="badge badge-info px-2 py-1" style="font-size: 0.65rem; font-weight: 700;">
                    #{{ $vehiculo->id }}
                </span>
            </small>
        </div>

        <div>
            <a href="{{ route('vehiculos.show', $vehiculo) }}"
               class="btn btn-sm btn-outline-info font-weight-bold mr-1 shadow-sm">
                <i class="fas fa-eye mr-1"></i> Ver Ficha
            </a>

            <a href="{{ route('vehiculos.index') }}"
               class="btn btn-sm btn-outline-secondary font-weight-bold shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>
@stop

@section('content')

@php
    $combustibles = [
        'Gasolina',
        'Diésel',
        'Híbrido',
        'Eléctrico',
        'Gas LP',
        'Gas Natural',
        'Otro'
    ];

    $fechaAdquisicion = old(
        'fecha_adquisicion',
        $vehiculo->fecha_adquisicion
            ? \Carbon\Carbon::parse($vehiculo->fecha_adquisicion)->format('Y-m-d')
            : ''
    );

    $fechaUltimoMantenimiento = old(
        'fecha_ultimo_mantenimiento',
        $vehiculo->fecha_ultimo_mantenimiento
            ? \Carbon\Carbon::parse($vehiculo->fecha_ultimo_mantenimiento)->format('Y-m-d')
            : ''
    );

    $estatusActual = (string) old('is_active', $vehiculo->is_active ? '1' : '0');
    $mostrarMotivo = $estatusActual === '0';

    /*
    |--------------------------------------------------------------------------
    | Indicadores operativos reales del vehículo
    |--------------------------------------------------------------------------
    | Se calculan con datos existentes de la BD:
    | - Mantenimiento: fecha_ultimo_mantenimiento + frecuencia_meses del tipo.
    | - Seguro: vehiculo_documentacion.vigencia_seguro.
    | - Vida útil: fecha_adquisicion + vida_util_estimada.
    */

    $indicadoresOperativos = [];

    // 1) Mantenimiento preventivo
    if (
        !$vehiculo->fecha_ultimo_mantenimiento ||
        !$vehiculo->tipoVehiculo ||
        !$vehiculo->tipoVehiculo->frecuencia_meses ||
        $vehiculo->tipoVehiculo->frecuencia_meses <= 0
    ) {
        $indicadoresOperativos[] = [
            'label' => 'Mantenimiento preventivo',
            'icon' => 'fa-tools',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay frecuencia o último mantenimiento registrado',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    } else {
        $ultimo = \Carbon\Carbon::parse($vehiculo->fecha_ultimo_mantenimiento);
        $proximo = $ultimo->copy()->addMonths($vehiculo->tipoVehiculo->frecuencia_meses);
        $hoy = \Carbon\Carbon::now();

        $diasTotales = max((int) round($ultimo->diffInDays($proximo)), 1);
        $diasRestantes = (int) round($hoy->diffInDays($proximo, false));
        $score = max(0, min(100, (int) round(($diasRestantes / $diasTotales) * 100)));

        if ($diasRestantes < 0) {
            $indicadoresOperativos[] = [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-tools',
                'score' => 0,
                'status' => 'Vencido',
                'detail' => 'Venció hace ' . abs($diasRestantes) . ' día(s)',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        } elseif ($diasRestantes <= 30) {
            $indicadoresOperativos[] = [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-tools',
                'score' => $score,
                'status' => 'Próximo',
                'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
                'class' => 'bg-warning',
                'badge' => 'badge-warning',
            ];
        } else {
            $indicadoresOperativos[] = [
                'label' => 'Mantenimiento preventivo',
                'icon' => 'fa-tools',
                'score' => $score,
                'status' => 'Al día',
                'detail' => 'Vence en ' . $diasRestantes . ' día(s)',
                'class' => 'bg-success',
                'badge' => 'badge-success',
            ];
        }
    }

    // 2) Seguro vehicular
    if (!$vehiculo->documentacion || !$vehiculo->documentacion->vigencia_seguro) {
        $indicadoresOperativos[] = [
            'label' => 'Seguro vehicular',
            'icon' => 'fa-shield-alt',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay vigencia de seguro registrada',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    } else {
        $vigenciaSeguro = \Carbon\Carbon::parse($vehiculo->documentacion->vigencia_seguro);
        $diasRestantesSeguro = (int) round(\Carbon\Carbon::now()->diffInDays($vigenciaSeguro, false));
        $scoreSeguro = max(0, min(100, (int) round(($diasRestantesSeguro / 365) * 100)));

        if ($diasRestantesSeguro < 0) {
            $indicadoresOperativos[] = [
                'label' => 'Seguro vehicular',
                'icon' => 'fa-shield-alt',
                'score' => 0,
                'status' => 'Vencido',
                'detail' => 'Venció hace ' . abs($diasRestantesSeguro) . ' día(s)',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        } elseif ($diasRestantesSeguro <= 30) {
            $indicadoresOperativos[] = [
                'label' => 'Seguro vehicular',
                'icon' => 'fa-shield-alt',
                'score' => $scoreSeguro,
                'status' => 'Por vencer',
                'detail' => 'Vence en ' . $diasRestantesSeguro . ' día(s)',
                'class' => 'bg-warning',
                'badge' => 'badge-warning',
            ];
        } else {
            $indicadoresOperativos[] = [
                'label' => 'Seguro vehicular',
                'icon' => 'fa-shield-alt',
                'score' => $scoreSeguro,
                'status' => 'Vigente',
                'detail' => 'Vence en ' . $diasRestantesSeguro . ' día(s)',
                'class' => 'bg-success',
                'badge' => 'badge-success',
            ];
        }
    }

    // 3) Vida útil del activo
    if (!$vehiculo->fecha_adquisicion || !$vehiculo->vida_util_estimada || $vehiculo->vida_util_estimada <= 0) {
        $indicadoresOperativos[] = [
            'label' => 'Vida útil del activo',
            'icon' => 'fa-chart-line',
            'score' => 0,
            'status' => 'Sin dato',
            'detail' => 'No hay fecha de adquisición o vida útil registrada',
            'class' => 'bg-secondary',
            'badge' => 'badge-secondary',
        ];
    } else {
        $fechaAdq = \Carbon\Carbon::parse($vehiculo->fecha_adquisicion);
        $finVidaUtil = $fechaAdq->copy()->addMonths($vehiculo->vida_util_estimada);
        $mesesRestantes = (int) round(\Carbon\Carbon::now()->diffInMonths($finVidaUtil, false));
        $scoreVida = max(0, min(100, (int) round(($mesesRestantes / max($vehiculo->vida_util_estimada, 1)) * 100)));

        if ($mesesRestantes < 0) {
            $indicadoresOperativos[] = [
                'label' => 'Vida útil del activo',
                'icon' => 'fa-chart-line',
                'score' => 0,
                'status' => 'Agotada',
                'detail' => 'Superó su vida útil estimada',
                'class' => 'bg-danger',
                'badge' => 'badge-danger',
            ];
        } elseif ($mesesRestantes <= 6) {
            $indicadoresOperativos[] = [
                'label' => 'Vida útil del activo',
                'icon' => 'fa-chart-line',
                'score' => $scoreVida,
                'status' => 'Finalizando',
                'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es)',
                'class' => 'bg-warning',
                'badge' => 'badge-warning',
            ];
        } else {
            $indicadoresOperativos[] = [
                'label' => 'Vida útil del activo',
                'icon' => 'fa-chart-line',
                'score' => $scoreVida,
                'status' => 'Vigente',
                'detail' => 'Restan aprox. ' . $mesesRestantes . ' mes(es)',
                'class' => 'bg-success',
                'badge' => 'badge-success',
            ];
        }
    }

@endphp

@if ($errors->any())
    <div class="callout callout-danger alert alert-dismissible fade show shadow-sm border-0 mb-3" role="alert"
         style="border-left: 5px solid #dc3545 !important; background-color: #ffffff;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="opacity: 0.5; outline: none;">
            <span aria-hidden="true">&times;</span>
        </button>

        <div class="d-flex align-items-center">
            <div class="text-danger mr-3">
                <i class="fas fa-exclamation-circle fa-2x"></i>
            </div>
            <div>
                <h5 class="text-danger font-weight-bold mb-0" style="font-size: 1.05rem;">
                    Revisa la información
                </h5>
                <p class="mb-0 text-muted small">
                    Hay campos requeridos o con formato inválido.
                </p>
            </div>
        </div>
    </div>
@endif

{{-- Contexto Empresa --}}
<div class="d-flex border-0 shadow-sm p-3 bg-white mb-3 align-items-center justify-content-between" style="border-radius: 12px;">
    <div class="d-flex align-items-center">
        <div class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center mr-3 shadow-sm"
             style="width: 40px; height: 40px;">
            <i class="fas fa-building"></i>
        </div>

        <div>
            <span class="text-muted d-block text-xs font-weight-bold text-uppercase" style="letter-spacing: 0.5px;">
                Contexto operativo de edición
            </span>

            <strong class="text-uppercase text-dark" style="font-size: 1.05rem;">
                {{ $vehiculo->empresa->nombre ?? App\Models\Empresa::find(session('empresa_id'))->nombre ?? 'Sin Empresa Asignada' }}
            </strong>
        </div>
    </div>

    <div>
        <a href="{{ route('vehiculos.cambiar_empresa') }}"
           class="btn btn-outline-secondary btn-sm px-3 font-weight-bold shadow-sm"
           style="border-radius: 8px;">
            <i class="fas fa-exchange-alt mr-1"></i> Cambiar Empresa
        </a>
    </div>
</div>

<div class="inventory-wrapper">

    {{-- Formulario Principal --}}
    <div class="card card-outline card-info shadow-sm form-container">
        <form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="card-header bg-white p-3 d-flex align-items-center">
                <h3 class="card-title text-info font-weight-bold text-sm mb-0">
                    <i class="fas fa-sliders-h mr-1"></i> PARÁMETROS DEL ACTIVO VEHICULAR
                </h3>

                <div class="ml-auto">
                    @if($vehiculo->is_active)
                        <span class="badge badge-success px-2 py-1 font-weight-bold">ACTIVO</span>
                    @else
                        <span class="badge badge-danger px-2 py-1 font-weight-bold">INACTIVO</span>
                    @endif
                </div>
            </div>

            <div class="card-body">

                {{-- Identificación --}}
                <div class="form-section-title">
                    <i class="fas fa-car-side mr-1"></i> Identificación del Vehículo
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Tipo de Vehículo <span class="required-mark">*</span></label>
                        <select name="tipo_vehiculo_id"
                                class="custom-select @error('tipo_vehiculo_id') is-invalid @enderror"
                                required>
                            <option value="">Selecciona una opción...</option>
                            @foreach($tiposVehiculo as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ old('tipo_vehiculo_id', $vehiculo->tipo_vehiculo_id) == $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_vehiculo_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Marca <span class="required-mark">*</span></label>
                        <select name="marca_id"
                                class="custom-select @error('marca_id') is-invalid @enderror"
                                required>
                            <option value="">Selecciona una opción...</option>
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    {{ old('marca_id', $vehiculo->marca_id) == $marca->id ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('marca_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Modelo <span class="required-mark">*</span></label>
                        <input type="text"
                               name="modelo"
                               class="form-control @error('modelo') is-invalid @enderror"
                               value="{{ old('modelo', $vehiculo->modelo) }}"
                               maxlength="255"
                               required>
                        @error('modelo')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Año <span class="required-mark">*</span></label>
                        <input type="number"
                               name="anio"
                               class="form-control @error('anio') is-invalid @enderror"
                               value="{{ old('anio', $vehiculo->anio) }}"
                               min="1900"
                               max="{{ date('Y') + 1 }}"
                               required>
                        @error('anio')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Placas</label>
                        <input type="text"
                               name="placas"
                               class="form-control @error('placas') is-invalid @enderror"
                               value="{{ old('placas', $vehiculo->placas) }}"
                               maxlength="20">
                        @error('placas')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>No. Serie</label>
                        <input type="text"
                               name="no_serie"
                               class="form-control @error('no_serie') is-invalid @enderror"
                               value="{{ old('no_serie', $vehiculo->no_serie) }}"
                               maxlength="50">
                        @error('no_serie')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>No. Motor</label>
                        <input type="text"
                               name="no_motor"
                               class="form-control @error('no_motor') is-invalid @enderror"
                               value="{{ old('no_motor', $vehiculo->no_motor) }}"
                               maxlength="50">
                        @error('no_motor')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Cilindros</label>
                        <input type="number"
                               name="cilindros"
                               class="form-control @error('cilindros') is-invalid @enderror"
                               value="{{ old('cilindros', $vehiculo->cilindros) }}"
                               min="1">
                        @error('cilindros')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Pedimento</label>
                        <input type="text"
                            name="pedimento"
                            class="form-control @error('pedimento') is-invalid @enderror"
                            value="{{ old('pedimento', $vehiculo->pedimento) }}"
                            maxlength="100"
                            placeholder="Ej. 23 48 1234 5001234">
                        <small class="text-muted">
                            Solo aplica para vehículos importados o regularizados.
                        </small>
                        @error('pedimento')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Cuenta contable:</label>
                        <input type="text"
                            name="cuenta_contable"
                            class="form-control @error('cuenta_contable') is-invalid @enderror"
                            value="{{ old('cuenta_contable', $vehiculo->cuenta_contable) }}"
                            maxlength="100"
                            placeholder="Ej. 12345">
                        @error('cuenta_contable')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>


                </div>

                {{-- Asignación --}}
                <div class="form-section-title mt-3">
                    <i class="fas fa-user-check mr-1"></i> Asignación y Operación
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Resguardante <span class="required-mark">*</span></label>
                        <select name="usuario_id"
                                class="custom-select @error('usuario_id') is-invalid @enderror"
                                required>
                            <option value="">Selecciona una opción...</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}"
                                    {{ old('usuario_id', $vehiculo->usuario_id) == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('usuario_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Ubicación Base <span class="required-mark">*</span></label>
                        <select name="ubicacion_id"
                                class="custom-select @error('ubicacion_id') is-invalid @enderror"
                                required>
                            <option value="">Selecciona una opción...</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->id }}"
                                    {{ old('ubicacion_id', $vehiculo->ubicacion_id) == $ubicacion->id ? 'selected' : '' }}>
                                    {{ $ubicacion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('ubicacion_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Tipo de Combustible</label>
                        <select name="tipo_combustible"
                                class="custom-select @error('tipo_combustible') is-invalid @enderror">
                            <option value="">No especificado</option>
                            @foreach($combustibles as $combustible)
                                <option value="{{ $combustible }}"
                                    {{ old('tipo_combustible', $vehiculo->tipo_combustible) == $combustible ? 'selected' : '' }}>
                                    {{ $combustible }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_combustible')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Patrimonial --}}
                <div class="form-section-title mt-3">
                    <i class="fas fa-file-invoice-dollar mr-1"></i> Datos Patrimoniales
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Valor Inicial</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">$</span>
                            </div>
                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="valor_inicial"
                                   class="form-control @error('valor_inicial') is-invalid @enderror"
                                   value="{{ old('valor_inicial', $vehiculo->valor_inicial) }}">
                        </div>
                        @error('valor_inicial')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Fecha de Adquisición</label>
                        <input type="date"
                               name="fecha_adquisicion"
                               class="form-control @error('fecha_adquisicion') is-invalid @enderror"
                               value="{{ $fechaAdquisicion }}">
                        @error('fecha_adquisicion')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Vida Útil Estimada</label>
                        <input type="number"
                               min="0"
                               name="vida_util_estimada"
                               class="form-control @error('vida_util_estimada') is-invalid @enderror"
                               value="{{ old('vida_util_estimada', $vehiculo->vida_util_estimada) }}"
                               placeholder="Ej. 60">
                        @error('vida_util_estimada')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Último Mantenimiento</label>
                        <input type="date"
                               name="fecha_ultimo_mantenimiento"
                               class="form-control @error('fecha_ultimo_mantenimiento') is-invalid @enderror"
                               value="{{ $fechaUltimoMantenimiento }}">
                        @error('fecha_ultimo_mantenimiento')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Estatus --}}
                <div class="form-section-title mt-3">
                    <i class="fas fa-power-off mr-1"></i> Estatus Operativo
                </div>

                <div class="status-box">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label>Estatus <span class="required-mark">*</span></label>
                            <select name="is_active"
                                    id="is_active"
                                    class="custom-select @error('is_active') is-invalid @enderror"
                                    required>
                                <option value="1" {{ $estatusActual === '1' ? 'selected' : '' }}>
                                    Activo
                                </option>
                                <option value="0" {{ $estatusActual === '0' ? 'selected' : '' }}>
                                    Inactivo
                                </option>
                            </select>
                            @error('is_active')
                                <span class="input-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-8"
                             id="motivo_container"
                             style="{{ $mostrarMotivo ? '' : 'display: none;' }}">
                            <label>Motivo de Inactivación</label>
                            <textarea name="motivo_inactivacion"
                                      id="motivo_inactivacion"
                                      class="form-control @error('motivo_inactivacion') is-invalid @enderror"
                                      rows="2"
                                      maxlength="255"
                                      {{ $mostrarMotivo ? 'required' : '' }}>{{ old('motivo_inactivacion', $vehiculo->motivo_inactivacion) }}</textarea>
                            @error('motivo_inactivacion')
                                <span class="input-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer unificado --}}
            <div class="card-footer bg-white border-top-0 d-flex align-items-center justify-content-between py-2">
                <div>
                    <a href="{{ route('vehiculos.index') }}"
                       class="btn btn-outline-secondary btn-premium">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </a>

                    <button type="submit"
                            class="btn btn-info btn-premium shadow-sm px-4">
                        <i class="fas fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>

                <div class="d-flex align-items-center ml-auto" style="opacity: 0.85;">
                    <div class="mx-2 d-none d-md-block" style="border-left: 1px solid #e2e8f0; height: 35px;"></div>
                    <div class="text-right mr-2 d-none d-lg-block">
                        <small class="text-muted d-block" style="font-size: 0.55rem; line-height: 1; letter-spacing: 0.8px; font-weight: 700;">
                            SISTEMA DE GESTIÓN
                        </small>
                        <span class="font-weight-bold text-dark" style="font-size: 0.75rem; letter-spacing: 0.3px;">
                            ACTIVOS TI
                        </span>
                    </div>
                    <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}"
                         alt="Logo PIHCSA"
                         style="height: 32px; width: auto; filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.08));">
                </div>
            </div>
        </form>
    </div>

    {{-- Panel Lateral --}}
    <div class="card card-outline card-info shadow-sm preview-sidebar">
        <div class="card-header p-3 d-flex align-items-center bg-light">
            <h3 class="card-title text-info font-weight-bold text-sm mb-0">
                <i class="fas fa-satellite-dish mr-1 text-xs"></i> TELEMETRÍA DE ACTIVO
            </h3>
        </div>

        <div class="card-body p-3">

            <div class="text-center pb-3 border-bottom mb-3">
                <div class="p-3 d-inline-block rounded-circle bg-light text-info mb-2 shadow-sm"
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-car fa-2x"></i>
                </div>

                <h5 class="font-weight-bold text-dark mb-1">
                    {{ $vehiculo->tipoVehiculo->nombre ?? 'Vehículo' }}
                    {{ $vehiculo->marca->nombre ?? 'N/A' }}
                </h5>

                <span class="badge badge-info px-2 py-1 font-weight-bold text-xs shadow-sm">
                    Placas: {{ $vehiculo->placas ?? 'S/P' }}
                </span>
            </div>

            <div class="mb-3">
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">
                    Indicadores Operativos Reales
                </span>

                @foreach($indicadoresOperativos as $indicador)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                            <span>
                                <i class="fas {{ $indicador['icon'] }} mr-1 text-muted"></i>
                                {{ $indicador['label'] }}
                            </span>

                            <span class="badge {{ $indicador['badge'] }} px-2 py-1">
                                {{ $indicador['status'] }}
                            </span>
                        </div>

                        <div class="metric-progress">
                            <div class="metric-progress-bar {{ $indicador['class'] }}"
                                style="width: {{ $indicador['score'] }}%;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">
                                {{ $indicador['detail'] }}
                            </small>

                            <small class="font-weight-bold text-dark">
                                {{ $indicador['score'] }}%
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-3">
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-2">
                    Identificación Técnica
                </span>

                <div class="bg-light p-2 rounded" style="font-size: 0.82rem;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Modelo:</span>
                        <strong>{{ $vehiculo->modelo }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Año:</span>
                        <strong>{{ $vehiculo->anio }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Serie:</span>
                        <strong class="text-truncate" style="max-width: 180px;">
                            {{ $vehiculo->no_serie ?? 'N/A' }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-1">
                        <span class="text-muted">Pedimento:</span>
                        <strong class="text-truncate" style="max-width: 180px;">
                            {{ $vehiculo->pedimento ?: 'N/A' }}
                        </strong>
                    </div>
                    
                </div>
            </div>

            <div>
                <span class="text-xs text-muted font-weight-bold text-uppercase d-block mb-1">
                    Estado Operativo
                </span>

                <div class="p-2 border rounded bg-dark d-flex align-items-center shadow-sm">
                    @if($vehiculo->is_active)
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <span class="text-xs text-monospace" style="color: #a3e635;">Activo en operación</span>
                    @else
                        <i class="fas fa-ban text-danger mr-2"></i>
                        <span class="text-xs text-monospace" style="color: #fca5a5;">Activo inactivo</span>
                    @endif
                </div>

                @if(!$vehiculo->is_active && $vehiculo->motivo_inactivacion)
                    <small class="text-muted d-block mt-2">
                        <strong>Motivo:</strong> {{ $vehiculo->motivo_inactivacion }}
                    </small>
                @endif
            </div>

        </div>
    </div>

</div>

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectActive = document.getElementById('is_active');
    const motivoContainer = document.getElementById('motivo_container');
    const motivoInput = document.getElementById('motivo_inactivacion');

    function toggleMotivo() {
        if (!selectActive || !motivoContainer || !motivoInput) return;

        if (selectActive.value === '0') {
            motivoContainer.style.display = 'block';
            motivoInput.required = true;
            motivoInput.focus();
        } else {
            motivoContainer.style.display = 'none';
            motivoInput.required = false;
            motivoInput.value = '';
        }
    }

    if (selectActive) {
        selectActive.addEventListener('change', toggleMotivo);
        toggleMotivo();
    }
});
</script>
@stop