@extends('adminlte::page')

@section('title', 'Detalles del Vehículo')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">
                Detalles del Vehículo:
                {{ $vehiculo->marca->nombre ?? 'N/A' }}
                {{ $vehiculo->modelo }}
            </h1>

            <small class="text-muted">
                ID del sistema:
                <span class="badge badge-dark">#{{ $vehiculo->id }}</span>
            </small>
        </div>

        <div>
            <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-info mr-2">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>

            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Inventario
            </a>
        </div>
    </div>
@stop

@section('content')

@php
    $formatoFecha = function ($fecha) {
        return $fecha ? \Carbon\Carbon::parse($fecha)->format('d/m/Y') : 'N/A';
    };

    $formatoFechaHora = function ($fecha) {
        return $fecha ? \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') : 'N/A';
    };

    $formatoDinero = function ($valor) {
        return is_numeric($valor) ? '$' . number_format($valor, 2) : 'N/A';
    };
@endphp

<div class="row">

    {{-- Ficha Técnica --}}
    <div class="col-md-6">
        <div class="card card-outline card-primary shadow">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-car mr-2"></i> Ficha Técnica
                </h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-striped my-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 40%;">Empresa:</td>
                            <td>
                                <span class="badge badge-info px-2 py-1 font-weight-bold">
                                    {{ $vehiculo->empresa->nombre ?? 'Sin empresa asignada' }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Tipo de Activo:</td>
                            <td>{{ $vehiculo->tipoVehiculo->nombre ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Marca:</td>
                            <td>{{ $vehiculo->marca->nombre ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Modelo / Versión:</td>
                            <td>{{ $vehiculo->modelo ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Año Modelo:</td>
                            <td>{{ $vehiculo->anio ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Placas:</td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $vehiculo->placas ?: 'Sin placas' }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Número de Serie (VIN):</td>
                            <td><code>{{ $vehiculo->no_serie ?: 'N/A' }}</code></td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Número de Motor:</td>
                            <td><code>{{ $vehiculo->no_motor ?: 'N/A' }}</code></td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Cilindros:</td>
                            <td>{{ $vehiculo->cilindros ?: 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Pedimento:</td>
                            <td><code>{{ $vehiculo->pedimento ?: 'N/A' }}</code></td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Tipo de Combustible:</td>
                            <td>{{ $vehiculo->tipo_combustible ?: 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Datos Financieros --}}
        <div class="card card-outline card-warning shadow mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-dollar-sign mr-2"></i> Control Financiero y Ciclo de Vida
                </h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-striped my-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 40%;">Valor Inicial:</td>
                            <td>{{ $formatoDinero($vehiculo->valor_inicial) }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Fecha de Adquisición:</td>
                            <td>{{ $formatoFecha($vehiculo->fecha_adquisicion) }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Fecha Inicio de Uso:</td>
                            <td>{{ $formatoFecha($vehiculo->fecha_inicio_uso) }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Vida Útil Estimada:</td>
                            <td>
                                {{ $vehiculo->vida_util_estimada ? $vehiculo->vida_util_estimada . ' mes(es)' : 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Último Mantenimiento:</td>
                            <td>
                                {{ $vehiculo->fecha_ultimo_mantenimiento ? $formatoFecha($vehiculo->fecha_ultimo_mantenimiento) : 'Ninguno registrado' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Control Operativo --}}
    <div class="col-md-6">
        <div class="card card-outline card-info shadow">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-check mr-2"></i> Control Operativo
                </h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-striped my-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 40%;">Usuario Responsable:</td>
                            <td>{{ $vehiculo->usuario->name ?? 'Sin asignar' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Correo Responsable:</td>
                            <td>{{ $vehiculo->usuario->email ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Departamento:</td>
                            <td>{{ $vehiculo->usuario->departamento ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Ubicación Asignada:</td>
                            <td>{{ $vehiculo->ubicacion->nombre ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Estatus de Alerta:</td>
                            <td>
                                @if($vehiculo->estatus_mantenimiento === 'rojo')
                                    <span class="badge badge-danger">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Plazo vencido
                                    </span>
                                @elseif($vehiculo->estatus_mantenimiento === 'amarillo')
                                    <span class="badge badge-warning text-dark">
                                        <i class="fas fa-clock mr-1"></i> Próximo a mantenimiento
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i> Operación segura
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Estado del Activo:</td>
                            <td>
                                @if($vehiculo->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>

                                    <div class="small text-danger mt-1">
                                        <strong>Motivo:</strong>
                                        {{ $vehiculo->motivo_inactivacion ?: 'Sin motivo registrado' }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Datos del Sistema --}}
        <div class="card card-outline card-secondary shadow mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-database mr-2"></i> Datos del Sistema
                </h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-striped my-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" style="width: 40%;">ID Registro:</td>
                            <td><code>{{ $vehiculo->id }}</code></td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Fecha de Registro:</td>
                            <td>{{ $formatoFechaHora($vehiculo->created_at) }}</td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Última Actualización:</td>
                            <td>{{ $formatoFechaHora($vehiculo->updated_at) }}</td>
                        </tr>

                        @if(isset($vehiculo->deleted_at))
                            <tr>
                                <td class="font-weight-bold">Fecha de Eliminación:</td>
                                <td>{{ $formatoFechaHora($vehiculo->deleted_at) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@stop