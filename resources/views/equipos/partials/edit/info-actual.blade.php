<div class="card card-outline card-info h-100"> {{-- h-100 ayuda a estirar si usas flex --}}
    <div class="card-header">
        <h3 class="card-title text-primary"><i class="fas fa-info-circle"></i> Datos Actuales</h3>
    </div>
    <div class="card-body">
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto px-2 text-primary small text-bold"><i class="fas fa-cogs"></i> Especificaciones Generales</legend>
            
            <div class="data-item">
                <span class="data-label text-muted">Marca:</span> 
                <span class="float-right">{{ $equipo->marca?->nombre ?? 'Sin Marca' }}</span>
            </div>
            <div class="data-item">
                <span class="data-label text-muted">Modelo:</span> 
                <span class="float-right">{{ $equipo->modelo ?? 'Sin Modelo' }}</span>
            </div>
            <div class="data-item">
                <span class="data-label text-muted">Tipo:</span> 
                <span class="float-right">{{ $equipo->tipoActivo?->nombre ?? 'N/A' }}</span>
            </div>
            <div class="data-item">
                <span class="data-label text-muted">Serial:</span> 
                <span class="float-right text-bold">{{ $equipo->serial }}</span>
            </div>
            <div class="data-item">
                <span class="data-label text-muted"><i class="fab fa-windows"></i> OS:</span> 
                <span class="float-right">{{ $equipo->sistema_operativo }}</span>
            </div>
        </fieldset>

        <fieldset class="border p-3 mb-4">
            <legend class="w-auto px-2 text-primary small text-bold"><i class="fas fa-user-shield"></i> Asignación</legend>
            <div class="data-item">
                <span class="data-label text-muted">Usuario:</span> 
                <span class="float-right text-primary text-bold">{{ $equipo->usuario->name ?? 'Sin asignar' }}</span>
            </div>
            <div class="data-item">
                <span class="data-label text-muted">Ubicación:</span> 
                <span class="float-right">{{ $equipo->ubicacion->nombre ?? 'Sin ubicar' }}</span>
            </div>
        </fieldset>

        <fieldset class="border p-3 mb-4">
            <legend class="w-auto px-2 text-primary small text-bold">
                <i class="fas fa-puzzle-piece"></i> Historial Completo de Componentes
            </legend>
            
            {{-- Perifericos --}}
            <p class="mb-1 text-bold"><i class="fas fa-keyboard text-warning"></i> Perifericos</p>
            <ul class="list-unstyled mb-3 ml-2 border-left pl-2">
                @forelse($equipo->perifericos as $p)
                    <li class="mb-2 {{ !$p->is_active ? 'text-muted' : '' }}">
                        <small>
                            <i class="fas fa-caret-right"></i> 
                            <strong>{{ $p->tipo }}</strong> | {{ $p->marca }} | SN: {{ $p->serial ?? 'N/A' }}
                            @if(!$p->is_active) <span class="badge badge-danger">OFF</span> @endif
                        </small>
                    </li>
                @empty
                    <li class="text-muted small italic">Sin periféricos registrados.</li>
                @endforelse
            </ul>

            {{-- RAM --}}
            <p class="mb-1 text-bold"><i class="fas fa-memory text-warning"></i> Memorias RAM</p>
            <ul class="list-unstyled mb-3 ml-2 border-left pl-2">
                @forelse($equipo->rams as $r)
                    <li class="mb-2 {{ !$r->is_active ? 'text-muted' : '' }}">
                        <small>
                            <i class="fas fa-caret-right"></i> 
                            <strong>{{ $r->capacidad_gb }}GB</strong> | {{ $r->tipo_chz }} | {{ $r->clock_mhz }}MHz
                            @if(!$r->is_active) <span class="badge badge-danger">OFF</span> @endif
                        </small>
                    </li>
                @empty
                    <li class="text-muted small italic">Sin memorias RAM registradas.</li>
                @endforelse
            </ul>

            {{-- Procesador --}}
            <p class="mb-1 text-bold"><i class="fas fa-microchip text-warning"></i> Procesadores</p>
            <ul class="list-unstyled mb-3 ml-2 border-left pl-2">
                @forelse($equipo->procesadores as $proc)
                    <li class="mb-2 {{ !$proc->is_active ? 'text-muted' : '' }}">
                        <small>
                            <i class="fas fa-caret-right"></i> 
                            <strong>{{ $proc->marca }}</strong> | {{ $proc->descripcion_tipo }}
                            @if(!$proc->is_active) <span class="badge badge-danger">OFF</span> @endif
                        </small>
                    </li>
                @empty
                    <li class="text-muted small italic">Sin procesador registrado.</li>
                @endforelse
            </ul>

            {{-- Monitores --}}
            <p class="mb-1 text-bold"><i class="fas fa-tv text-warning"></i> Monitores</p>
            <ul class="list-unstyled mb-3 ml-2 border-left pl-2">
                @forelse($equipo->monitores as $mon)
                    <li class="mb-2 {{ !$mon->is_active ? 'text-muted' : '' }}">
                        <small>
                            <i class="fas fa-caret-right"></i> 
                            <strong>{{ $mon->marca }}</strong> | {{ $mon->escala_pulgadas }}" | {{ $mon->interface }} | SN: {{ $mon->serial ?? 'N/A' }}
                            @if(!$mon->is_active) <span class="badge badge-danger">OFF</span> @endif
                        </small>
                    </li>
                @empty
                    <li class="text-muted small italic">Sin monitores registrados.</li>
                @endforelse
            </ul>

            {{-- Discos --}}
            <p class="mb-1 text-bold"><i class="fas fa-hdd text-warning"></i> Almacenamiento</p>
            <ul class="list-unstyled mb-0 ml-2 border-left pl-2">
                @forelse($equipo->discosDuros as $dd)
                    <li class="mb-2 {{ !$dd->is_active ? 'text-muted' : '' }}">
                        <small>
                            <i class="fas fa-caret-right"></i> 
                            <strong>{{ $dd->capacidad }}</strong> | {{ $dd->tipo_hdd_ssd }} | {{ $dd->interface }} | SN: {{ $dd->serial ?? 'N/A' }}
                            @if(!$dd->is_active) <span class="badge badge-danger">OFF</span> @endif
                        </small>
                    </li>
                @empty
                    <li class="text-muted small italic">Sin discos registrados.</li>
                @endforelse
            </ul>
        </fieldset>
    </div>
</div>