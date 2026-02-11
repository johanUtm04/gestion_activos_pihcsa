        
        <!-- Flash Data -->
        @if(session('danger'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-ban mr-2"></i>
                {!! session('danger') !!}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle mr-3 fa-lg"></i>
                    <div>
                        {!! session('warning') !!}
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert" style="border: none; border-left: 5px solid #1e7e34;">
                <i class="fas fa-check-circle fa-2x mr-3 opacity-8"></i>
                <div class="flex-grow-1">
                    <span class="h5 mb-0" style="vertical-align: middle;">
                        {!! session('success') !!}
                    </span>
                </div>
                <button type="button" class="close position-relative" data-dismiss="alert" aria-label="Close" style="padding: 0 1rem;">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
        @endif  <!--fin Flash Data -->
        

        <div class="card card-outline card-info shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-assets mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th>Activo / Serial</th>
                                <th>Asignado a</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipos as $equipo)
                            <tr id="equipo-{{ $equipo->id }}" 
                                class="equipo-row clickable-row" 
                                data-url="{{ route('equipos.show', $equipo->id) }}"
                                data-id="{{ $equipo->id }}"
                                data-marca="{{ $equipo->marca?->nombre ?? 'Genérica' }}"
                                data-tipo="{{ $equipo->tipoActivo?->nombre ?? 'Generico' }}"
                                data-serial="{{ $equipo->serial }}"
                                data-so="{{ $equipo->sistema_operativo }}"
                                data-usuario="{{ $equipo->usuario->name ?? 'Sin asignar' }}"
                                data-email="{{ $equipo->usuario->email ?? '-' }}"
                                data-ubicacion="{{ $equipo->ubicacion->nombre ?? 'Sin ubicación' }}"
                                data-valor="{{ number_format($equipo->valor_inicial, 2) }}"
                                data-fecha="{{ $equipo->fecha_adquisicion ?? 'Sin registro' }}"
                                data-vida="{{ $equipo->vida_util_estimada ?? 'N/A' }}"
                                data-monitores="{{ $equipo->monitores->count() }}"
                                data-discos="{{ $equipo->discosDuros->count() }}"
                                data-ram="{{ $equipo->rams->pluck('capacidad_gb')->implode('GB, ') }}GB"
                                data-perifericos="{{ $equipo->perifericos->pluck('tipo')->implode(', ') }}"
                                data-procesadores="{{ $equipo->procesadores->count() }}"
                                style="cursor: pointer;">
                                
                                <td class="text-center font-weight-bold text-muted">{{ $equipo->id }}</td>
                                <td>
                        
                                    @if(session('actualizado_id') == $equipo->id)
                                        <span class="badge badge-warning">Editado</span>
                                    @endif
                                    @if(session('new_id') == $equipo->id)
                                        <span class="badge badge-success">Nuevo Activo</span>
                                    @endif

                                    @if(session('new_mantenimiento') == $equipo->id)
                                        <span class="badge badge-secondary">Mantenimiento Registrado</span>
                                    @endif
                                    <div class="font-weight-bold text-dark">
                                    {{ $equipo->tipoActivo?->nombre ?? 'Sin Tipo' }} 
                                    {{ $equipo->marca?->nombre ?? 'Sin Marca' }}
                                    </div>
                                    <span class="secondary-data"><i class="fas fa-barcode mr-1"></i>{{ $equipo->serial }}</span>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $equipo->usuario->name ?? 'Sin asignar' }}</div>
                                    <span class="secondary-data"><i class="fas fa-envelope mr-1"></i>{{ $equipo->usuario->email ?? '-' }}</span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="btn-group shadow-sm">
                                        @can('editar-equipo')
                                            <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-default text-warning" title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        @endcan

                                        @can('mantenimiento-equipo')
                                            <a href="{{ route('equipos.addwork', $equipo) }}" class="btn btn-sm btn-default text-primary" title="Mantenimiento">
                                                <i class="fas fa-tools"></i>
                                            </a>
                                        @endcan

                                        <a href="{{ route('equipos.show', ['uuid' => $equipo->id]) }}" class="btn btn-sm btn-default text-info" title="Ver Ficha">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @can('eliminar-equipo')
                                    <div class="d-inline"> {{-- Cambia el div por d-inline para que no rompa la fila --}}
                                        <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="motivo" id="motivo_hidden_{{ $equipo->id }}">
                                            <button type="button" 
                                                    class="btn btn-sm btn-default text-secondary btn-inactivar" 
                                                    data-nombre="{{ $equipo->tipoActivo?->nombre }} - {{ $equipo->serial }}"
                                                    data-motivo-input="#motivo_hidden_{{ $equipo->id }}"
                                                    onclick="ejecutarInactivacion(this)">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0">
                {{ $equipos->links() }}
            </div>
        </div>
