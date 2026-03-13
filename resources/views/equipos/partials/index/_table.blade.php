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

    @if(session('warning') && session('actualizado_id'))
        <div class="callout callout-warning alert alert-dismissible shadow-sm mb-4" 
            role="alert" 
            style="border-left-width: 5px; background-color: #fffaf0; position: relative; padding-right: 4rem;">
            
            {{-- El botón ahora se posiciona correctamente arriba a la derecha --}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" 
                    style="position: absolute; top: 10px; right: 15px; outline: none; opacity: 0.6;">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-warning font-weight-bold mb-1">
                        <i class="fas fa-edit mr-2"></i> ¡Equipo Actualizado!
                    </h5>
                    <p class="mb-0 text-muted">
                        Los datos generales del activo han sido modificados. ¿Deseas auditar los cambios en el historial?
                    </p>
                </div>
                <div class="ml-3">
                    <a href="{{ route('historial.index', ['equipo_id' => session('actualizado_id')]) }}" 
                    class="btn btn-warning btn-lg elevation-2 px-4 font-weight-bold"
                    style="transition: all 0.3s ease; text-decoration: none; color: #333;">
                        <i class="fas fa-history mr-2"></i> Ver Cambios #{{ session('actualizado_id') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if(session('success') && session('new_id'))
        <div class="callout callout-success shadow-sm mb-4" style="border-left-width: 5px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="top: 10px; right: 10px;">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-success font-weight-bold">
                        <i class="fas fa-check-circle mr-2"></i> ¡Registro Completado!
                    </h5>
                    <p class="mb-0 text-muted">
                        El equipo ha sido dado de alta correctamente. ¿Deseas verificar los detalles en el historial?
                    </p>
                </div>
                <div class="ml-3">
                    <a href="{{ route('historial.index', ['equipo_id' => session('new_id')]) }}" 
                    class="btn btn-success btn-lg elevation-2 px-4 font-weight-bold"
                    style="transition: all 0.3s ease; text-decoration: none;">
                        <i class="fas fa-history mr-2"></i> Ver Historial #{{ session('new_id') }}
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if(session('secondary') && session('new_mantenimiento'))
            {{-- Añadimos alert y alert-dismissible para habilitar el cierre vía JS --}}
            <div class="callout callout-secondary alert alert-dismissible shadow-sm mb-4" 
                role="alert" 
                style="border-left-width: 5px; background-color: #f8f9fa; position: relative; padding-right: 4rem;">
                
                {{-- Botón de cerrar con posicionamiento absoluto --}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" 
                        style="position: absolute; top: 10px; right: 15px; outline: none; opacity: 0.6;">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="text-secondary font-weight-bold mb-1">
                            <i class="fas fa-tools mr-2"></i> ¡Mantenimiento Registrado!
                        </h5>
                        <p class="mb-0 text-muted">
                            Se ha añadido una nueva bitácora de servicio al equipo. ¿Deseas revisarla ahora?
                        </p>
                    </div>
                    <div class="ml-3">
                        <a href="{{ route('historial.index', ['equipo_id' => session('new_mantenimiento')]) }}" 
                        class="btn btn-secondary btn-lg elevation-2 px-4 font-weight-bold"
                        style="transition: all 0.3s ease; text-decoration: none;">
                            <i class="fas fa-eye mr-2"></i> Ver Historial #{{ session('new_mantenimiento') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if(session('actualizado_factura'))
        <div class="callout callout-warning alert alert-dismissible shadow-sm mb-4" 
            role="alert" 
            style="border-left-width: 5px; background-color: #fffaf0; position: relative; padding-right: 3rem; border-radius: .25rem;">
            
            {{-- Botón de cierre con posicionamiento absoluto para evitar saltos de línea --}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" 
                    style="position: absolute; top: 10px; right: 15px; outline: none; opacity: 0.5; background: none; border: none; font-size: 1.5rem; line-height: 1;">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-warning font-weight-bold mb-1">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> ¡Factura Actualizada!
                    </h5>
                    <p class="mb-0 text-muted">
                        {!! session('success') !!} ¿Deseas ver el registro de este cambio en el historial?
                    </p>
                </div>
                <div class="ml-3">
                    <a href="{{ route('historial.index', ['equipo_id' => session('actualizado_factura')]) }}" 
                    class="btn btn-warning btn-lg elevation-2 px-4 font-weight-bold"
                    style="transition: all 0.3s ease; text-decoration: none; color: #333; background-color: #ffc107; border-color: #ffc107;">
                        <i class="fas fa-search-dollar mr-2"></i> Ver Historial #{{ session('actualizado_factura') }}
                    </a>
                </div>
            </div>
        </div>
        @endif


        {{-- Marcador para que el JS sepa a dónde ir --}}
        @if($targetId = (session('new_id') ?? session('actualizado_id') ?? session('actualizado_factura') ?? session('new_mantenimiento')))
            <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
        @endif
         <!--fin Flash Data -->
        

        <div class="card card-outline card-info shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; scrollbar-width: thin;">
                    <table class="table table-hover table-assets mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th>Activo / Serial</th>
                                <th>Asignado a</th>
                                @if(request('filter') == 'inactivos')
                                    <th>Motivo de Inactivación</th>
                                @endif
                                @if(request('filter') !== 'inactivos')
                                    <th class="text-center">Acciones</th>
                                    <th class="text-center">MANTENIMIENTO ANUAL</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equipos as $equipo)
                        <tr id="equipo-{{ $equipo->id }}" 
                            class="equipo-row {{ request('filter') !== 'inactivos' ? 'clickable-row' : '' }} {{ $equipo->trashed() ? 'row-inactive' : '' }}" 
                            data-url="{{ route('equipos.show', $equipo->id) }}"
                            data-id="{{ $equipo->id }}"
                            data-marca="Modelo: {{ $equipo->modelo ?? 'Sin Modelo' }}"
                            data-modelo="{{ $equipo->marca?->nombre ?? 'Genérica' }}"
                            data-tipo="{{ $equipo->tipoActivo?->nombre ?? 'Generico' }}"
                            data-serial="{{ $equipo->serial }}"
                            data-departamento=" Dpto: {{ $equipo->departamento_perteneciente ?? 'Sin Depeartamento' }}"
                            data-so="{{ $equipo->sistema_operativo }}"
                            data-usuario="{{ $equipo->usuario->name ?? 'Sin asignar' }}"
                            data-email="{{ $equipo->usuario->email ?? '-' }}"
                            data-ubicacion="{{ $equipo->ubicacion->nombre ?? 'Sin ubicación' }}"
                            data-valor="{{ number_format($equipo->valor_inicial, 2) }}"
                            data-fecha="{{ $equipo->fecha_adquisicion ?? 'Sin registro' }}"
                            data-vida="{{ $equipo->vida_util_estimada ?? 'N/A' }}"
                            data-discos="{{ $equipo->discosDuros->where('is_active', 1)->count() }}"
                            data-procesadores="{{ $equipo->procesadores->where('is_active', 1)->count() }}"
                                                        
                            data-monitores="{{ $equipo->monitores->where('is_active', 1)->count() }}"
                            data-discos_inactivos="{{ $equipo->discosDuros->where('is_active', 0)->count() }}"
                            data-procesadores_inactivos="{{ $equipo->procesadores->where('is_active', 0)->count() }}"
                            data-monitores_inactivos="{{ $equipo->monitores->where('is_active', 0)->count() }}"
                            data-perifericos_inactivos="{{ $equipo->perifericos->where('is_active', 0)->pluck('tipo')->implode(', ') }}"
                            data-ram="{{ $equipo->rams->where('is_active', 1)->pluck('capacidad_gb')->implode('GB, ') }}GB"
                            data-ram_inactiva="{{ $equipo->rams->where('is_active', 0)->sum('capacidad_gb') }}"
                            data-tipo_chz="{{ $equipo->rams->pluck('tipo')->implode(',') }}"
                            data-descripcion_tipo_ram="{{ $equipo->rams->pluck('descripcion_tipo')->implode(',') }}"
                            data-clock_mhz="{{ $equipo->rams->pluck('clock_mhz')->implode(',') }}"
            
                            data-perifericos="{{ $equipo->perifericos->where('is_active', 1)->pluck('tipo')->implode(', ') }}"
                            data-numero_factura="{{ $equipo->numero_factura ?? 'No asignada' }}"
                            style="cursor: pointer;">
                                
                                <td class="text-center font-weight-bold text-muted">
                                    {{ ($equipos->currentPage() - 1) * $equipos->perPage() + $loop->iteration }}
                                </td>
                                <td>
                        
                                    @if(session('actualizado_id') == $equipo->id)
                                        <span class="badge badge-warning">Editado</span>
                                    @endif

                                    @if(session('new_id') == $equipo->id)
                                        <span class="badge badge-success">Nuevo Activo</span>
                                    @endif

                                        
                                    @if(session('actualizado_factura') == $equipo->id)
                                        <span class="badge badge-warning">Factura</span>
                                    @endif

                                    @if(session('new_mantenimiento') == $equipo->id)
                                        <span class="badge badge-secondary">Mantenimiento Registrado</span>
                                    @endif

                                    @if($equipo->trashed())
                                        <span class="badge badge-dark mb-1">
                                        <i class="fas fa-archive mr-1"></i> INACTIVADO
                                        </span>
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

                                @if(request('filter') == 'inactivos')
                                        <td style="vertical-align: middle;">
                                            <span class="text-danger font-italic">
                                                <i class="fas fa-comment-dots mr-1"></i>
                                                {{ $equipo->motivo_inactivacion ?? 'No especificado' }}
                                            </span>
                                        </td>
                                    @endif

                                @if(request('filter') !== 'inactivos')
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

                                        @can('mantenimiento-equipo')
                                            <a href="{{ route('equipos.factura.edit', $equipo) }}" class="btn btn-sm btn-default text-success" title="Asignar Factura">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </a>
                                        @endcan

                                        <a href="{{ route('equipos.show', ['equipo' => $equipo->id]) }}" class="btn btn-sm btn-default text-info" title="Ver Ficha">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @can('inactivar-equipo')
                                        <div class="d-inline"> {{-- Cambia el div por d-inline para que no rompa la fila --}}
                                            <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST" class="d-inline" title="Inactivar">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="motivo" id="motivo_hidden_{{ $equipo->id }}">
                                                <button type="button" 
                                                        class="btn btn-sm btn-default text-danger btn-inactivar" 
                                                        data-nombre="{{ $equipo->tipoActivo?->nombre }} - {{ $equipo->serial }}"
                                                        data-motivo-input="#motivo_hidden_{{ $equipo->id }}"
                                                        onclick="ejecutarInactivacion(this)">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endcan

                                        <a href="{{ route('equipos.ticket', $equipo->id) }}" 
                                        class="btn btn-sm btn-default shadow-sm text-secondary" 
                                        title="Imprimir Código de Barras" 
                                        target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                    
                                </td>
                                <td class="text-center">
                                    @if($equipo->semaforo)
                                        <span class="badge {{ $equipo->semaforo->clase }} p-2 shadow-sm" style="min-width: 80px;">
                                            {{ $equipo->semaforo->texto }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin Datos</span>
                                    @endif
                                </td>

                                @endif
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <p class="h5">Sin Resultados</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 d-flex align-items-center justify-content-between">
                {{-- Contenedor de la Paginación --}}
                <div>
                    {{ $equipos->links() }}
                </div>

                {{-- Contenedor del Logo --}}
                
                <div class="d-flex align-items-center ml-auto" style="opacity: 0.9;">
                    <div class="mx-1 d-none d-md-block" style="border-left: 1px solid #e0e0e0; height: 45px;"></div>
                    <div class="text-right mr-2 d-none d-lg-block">
                        <small class="text-muted d-block" style="font-size: 0.55rem; line-height: 1; letter-spacing: 0.5px;">SISTEMA DE GESTIÓN</small>
                        <span class="font-weight-bold text-secondary" style="font-size: 0.75rem;">ACTIVOS TI</span>
                    </div>
                    <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}" 
                        alt="Logo PIHCSA" 
                        style="height: 40px; width: auto; filter: drop-shadow(0px 2px 2px rgba(0,0,0,0.1));">
                </div>
            </div>
        </div>
