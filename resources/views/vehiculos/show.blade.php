    @extends('adminlte::page')

    @section('title', 'Detalles del Vehículo')

    @section('content_header')
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="m-0 text-dark">Detalles del Vehículo: {{ $vehiculo->marca->nombre ?? 'N/A' }} {{ $vehiculo->modelo }}</h1>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Inventario
            </a>
        </div>
    @stop

    @section('content')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-primary shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-car mr-2"></i> Ficha Técnica</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped my-0">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold" style="width: 40%;">Empresa:</td>
                                    <td><span class="badge badge-info px-2 py-1 font-weight-bold">{{ $vehiculo->empresa->nombre ?? 'Sin Empresa Assigned' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Tipo de Activo:</td>
                                    <td>{{ $vehiculo->tipoVehiculo->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Año Modelo:</td>
                                    <td>{{ $vehiculo->anio }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Placas:</td>
                                    <td><span class="badge badge-secondary">{{ $vehiculo->placas ?? 'Sin Placas' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Número de Serie (VIN):</td>
                                    <td><code>{{ $vehiculo->no_serie ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Número de Motor:</td>
                                    <td><code>{{ $vehiculo->no_motor ?? 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Cilindros:</td>
                                    <td>{{ $vehiculo->cilindros ?? 'N/A' }}</td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">Pedimento:</td>
                                    <td>
                                        <code>{{ $vehiculo->pedimento ?: 'N/A' }}</code>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">Tipo de Combustible:</td>
                                    <td>{{ $vehiculo->tipo_combustible ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-outline card-info shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-check mr-2"></i> Control Operativo</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped my-0">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold" style="width: 40%;">Usuario Responsable:</td>
                                    <td>{{ $vehiculo->usuario->name ?? 'Sin asignar' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Ubicación Asignada:</td>
                                    <td>{{ $vehiculo->ubicacion->nombre ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Último Mantenimiento:</td>
                                    <td>{{ $vehiculo->fecha_ultimo_mantenimiento ? \Carbon\Carbon::parse($vehiculo->fecha_ultimo_mantenimiento)->format('d/m/Y') : 'Ninguno registrado' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Estatus de Alerta:</td>
                                    <td>
                                        @if($vehiculo->estatus_mantenimiento === 'rojo')
                                            <span class="badge badge-danger"><i class="fas fa-exclamation-circle mr-1"></i> Plazo Vencido</span>
                                        @elseif($vehiculo->estatus_mantenimiento === 'amarillo')
                                            <span class="badge badge-warning text-dark"><i class="fas fa-clock mr-1"></i> Próximo a Mantenimiento</span>
                                        @else
                                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Operación Segura (Al día)</span>
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
                                            <div class="small text-danger mt-1"><strong>Motivo:</strong> {{ $vehiculo->motivo_inactivacion }}</div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-outline card-success shadow mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-file-invoice mr-2"></i> Documentación y Seguro</h3>
                    </div>
                    <div class="card-body p-0">
                        @if($vehiculo->documentacion)
                            <table class="table table-sm table-striped my-0">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold" style="width: 40%;">No. Póliza de Seguro:</td>
                                        <td><code>{{ $vehiculo->documentacion->no_poliza_seguro ?? 'N/A' }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Vigencia del Seguro:</td>
                                        <td>{{ $vehiculo->documentacion->vigencia_seguro ? \Carbon\Carbon::parse($vehiculo->documentacion->vigencia_seguro)->format('d/m/Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Tarjeta de Circulación:</td>
                                        <td><code>{{ $vehiculo->documentacion->tarjeta_circulacion ?? 'N/A' }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="p-3 text-center text-muted">
                                <p class="mb-2"><i class="fas fa-folder-open fa-2x text-secondary"></i></p>
                                <span>No se ha registrado la documentación legal de este vehículo.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @stop