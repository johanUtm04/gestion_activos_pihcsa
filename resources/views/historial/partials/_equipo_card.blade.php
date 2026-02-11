<div class="card mb-3 shadow-sm border-0 overflow-hidden equipo-card-main">
    {{-- HEADER DEL EQUIPO --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3" 
         style="cursor: pointer; border-left: 6px solid @if($equipo->tipo_equipo == 'Laptop')#007bff@else#6f42c1@endif;"
         data-toggle="collapse" 
         data-target="#collapseEquipo{{ $equipo->id }}">
        
        <div class="d-flex align-items-center">
            <div class="icon-box mr-3 shadow-sm d-flex align-items-center justify-content-center {{ $equipo->tipo_equipo == 'Laptop' ? 'bg-primary-soft' : 'bg-purple-soft' }}" 
                 style="width: 50px; height: 50px; border-radius: 12px;">
                <i class="fas {{ $equipo->tipo_equipo == 'Laptop' ? 'fa-laptop text-primary' : 'fa-desktop text-purple' }} fa-lg"></i>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold text-dark">{{ $equipo->tipoActivo->nombre ?? 'Equipo sin nombre' }}</h6>
                <div class="d-flex gap-2 mt-1">
                    <span class="badge badge-light border text-muted px-2 mr-2">ID: {{ $equipo->id }}</span>
                    <small class="text-muted"><i class="fas fa-user-circle mr-1"></i>Dueño: {{ $equipo->usuario->name ?? 'Sin asignar' }}</small>
                </div>
            </div>
        </div>
        
        <div class="text-right d-none d-md-block">
            <span class="badge badge-pill badge-primary-soft text-primary px-3 mb-1">
                {{ $equipo->historials->count() }} Eventos
            </span>
            <br>
            <i class="fas fa-chevron-down text-gray-300 transition-icon"></i>
        </div>
    </div>

    {{-- CUERPO CON LÍNEA DE TIEMPO --}}
    <div id="collapseEquipo{{ $equipo->id }}" class="collapse {{ request('equipo_id') == $equipo->id ? 'show' : '' }}">
        <div class="card-body bg-light p-0">
            <div class="p-4 scroll-custom" style="max-height: 550px; overflow-y: auto;">
                <div class="timeline-v2">
                    @forelse($equipo->historials()->latest()->get() as $log)
                        @include('historial.partials._log_item', ['log' => $log])
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-ghost fa-3x text-muted mb-2 opacity-2"></i>
                            <p class="text-muted mt-3">Sin actividad registrada en este activo</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>