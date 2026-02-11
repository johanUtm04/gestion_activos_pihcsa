<div class="card mb-3 shadow-sm border-0 overflow-hidden equipo-card-main">
    {{-- HEADER DEL EQUIPO --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3" 
         style="cursor: pointer; border-left: 6px solid {{ $equipo->tipo_equipo == 'Laptop' ? '#007bff' : '#6f42c1' }};"
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
    {{-- El resto del código del colapsable permanece igual --}}
</div>