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

                        <tr>
                        <td class="font-weight-bold">Cuenta contable:</td>
                            <td>{{ $vehiculo->cuenta_contable ?: 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
                                {{ $vehiculo->mantenimientos->first() ? $formatoFecha($vehiculo->mantenimientos->first()->fecha_evento) : 'Ninguno registrado' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">Último Proveedor:</td>
                            <td>
                                {{ $vehiculo->mantenimientos->first()->proveedor ?? 'N/A' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-outline card-teal shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-teal">
                    <i class="fas fa-history mr-2"></i> Historial de Mantenimientos y Eventos
                </h3>
                <div class="card-tools">
                    <a href="{{ route('vehiculos.addwork', $vehiculo) }}" class="btn btn-sm btn-success font-weight-bold">
                        <i class="fas fa-plus-circle mr-1"></i> Registrar Evento
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                @if($vehiculo->mantenimientos->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0 font-weight-bold">No se han registrado eventos o mantenimientos para esta unidad todavía.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped my-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 10%">Fecha</th>
                                    <th style="width: 16%">Tipo de Evento</th>
                                    <th style="width: 12%">Proveedor</th>
                                    <th style="width: 10%">Kilometraje</th>
                                    <th>Contexto / Descripción</th>
                                    <th style="width: 10%">Costo</th>
                                    <th style="width: 12%">Registrado por</th>
                                    <th style="width: 12%" class="text-center">Documentos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehiculo->mantenimientos as $mantenimiento)
                                    <tr>
                                        <td class="font-weight-bold text-secondary">
                                            {{ $formatoFecha($mantenimiento->fecha_evento) }}
                                        </td>
                                        <td>
                                            @if(str_contains(strtolower($mantenimiento->tipo_evento), 'preventivo'))
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-shield-alt mr-1"></i> {{ $mantenimiento->tipo_evento }}</span>
                                            @elseif(str_contains(strtolower($mantenimiento->tipo_evento), 'correctivo'))
                                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> {{ $mantenimiento->tipo_evento }}</span>
                                            @elseif(str_contains(strtolower($mantenimiento->tipo_evento), 'combustible'))
                                                <span class="badge badge-info px-2 py-1"><i class="fas fa-gas-pump mr-1"></i> {{ $mantenimiento->tipo_evento }}</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-wrench mr-1"></i> {{ $mantenimiento->tipo_evento }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-dark">{{ $mantenimiento->proveedor ?: '—' }}</small>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold">{{ number_format($mantenimiento->kilometraje) }}</span> <small class="text-muted">KM</small>
                                        </td>
                                        <td class="text-wrap" style="max-width: 300px;">
                                            {{ $mantenimiento->contexto }}
                                        </td>
                                        <td class="font-weight-bold text-dark">
                                            {{ $mantenimiento->costo ? $formatoDinero($mantenimiento->costo) : '—' }}
                                        </td>
                                        <td>
                                            <small class="text-muted"><i class="fas fa-user mr-1"></i> {{ $mantenimiento->usuario->name ?? 'Sistema' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                @if($mantenimiento->orden_servicio_path)
                                                    <a href="{{ $mantenimiento->orden_servicio_ver_url }}" target="_blank" class="btn btn-outline-primary" title="Ver orden de servicio">
                                                        <i class="fas fa-file-alt"></i>
                                                    </a>
                                                @endif

                                                @if($mantenimiento->factura_path)
                                                    <a href="{{ $mantenimiento->factura_ver_url }}" target="_blank" class="btn btn-outline-success" title="Ver factura">
                                                        <i class="fas fa-file-invoice-dollar"></i>
                                                    </a>
                                                @endif

                                                <button type="button" class="btn btn-outline-warning" title="Adjuntar o reemplazar documentos" data-toggle="modal" data-target="#adjuntarModal{{ $mantenimiento->id }}">
                                                    <i class="fas fa-paperclip"></i>
                                                </button>
                                            </div>

                                            <div class="modal fade" id="adjuntarModal{{ $mantenimiento->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('mantenimientos.adjuntar', $mantenimiento) }}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Adjuntar o reemplazar documentos</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <div class="form-group">
                                                                    <label>Orden de servicio</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" id="orden_servicio{{ $mantenimiento->id }}" name="orden_servicio" accept=".pdf,.jpg,.jpeg,.png">
                                                                        <label class="custom-file-label" for="orden_servicio{{ $mantenimiento->id }}">
                                                                            {{ $mantenimiento->orden_servicio_path ? 'Reemplazar archivo actual...' : 'Seleccionar archivo...' }}
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Factura</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" id="factura{{ $mantenimiento->id }}" name="factura" accept=".pdf,.jpg,.jpeg,.png">
                                                                        <label class="custom-file-label" for="factura{{ $mantenimiento->id }}">
                                                                            {{ $mantenimiento->factura_path ? 'Reemplazar archivo actual...' : 'Seleccionar archivo...' }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        $(document).on('change', '.custom-file-input', function () {
            const fileName = this.files[0] ? this.files[0].name : 'Seleccionar archivo...';
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@stop