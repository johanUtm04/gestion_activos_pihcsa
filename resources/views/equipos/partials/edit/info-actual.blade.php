<div class="card card-outline card-info">
    <div class="card-header">
    <legend class="w-auto px-1 text-primary">Datos Actuales</legend>
    </div>
    <div class="card-body">
        <fieldset class="border p-3 mb-4">
        <legend class="w-auto px-2 text-primary"><i class="fas fa-cogs"></i>Especificaciones Generales</legend>
            
        {{-- Datos Principales --}}
        <div class="data-item">
            <span class="data-label">Marca:</span> 
            <span class="float-right">{{ $equipo->marca?->nombre ?? 'Sin Marca' }}</span>
        </div>
        <div class="data-item">
            <span class="data-label">Modelo:</span> 
            <span class="float-right">{{ $equipo->modelo ?? 'Sin Modelo' }}</span>
        </div>
        <div class="data-item">
            <span class="data-label">Tipo de Equipo:</span> 
            <span class="float-right">{{ $equipo->tipoActivo?->nombre ?? 'Sin Marca' }}</span>
        </div>
        <div class="data-item">
            <span class="data-label">Serial:</span> 
            <span class="float-right text-bold">{{ $equipo->serial }}</span>
        </div>
        <div class="data-item">
            <span class="data-label"><i class="fab fa-windows"></i> S. Operativo:</span> 
            <span class="float-right">{{ $equipo->sistema_operativo }}</span>
        </div>
        
        <hr class="mt-4">

        <legend class="w-auto px-2 text-primary"><i class="fas fa-cog"></i>Responsabilidad y Ubicacion</legend>

        <div class="data-item">
            <span class="data-label"><i class="fas fa-user-tag"></i> Usuario:</span> 
            <span class="float-right text-primary">{{ $equipo->usuario->name ?? 'Sin asignar' }}</span>
        </div>
        <div class="data-item">
            <span class="data-label"><i class="fas fa-map-marker-alt"></i> Ubicacion:</span> 
            <span class="float-right text-primary">{{ $equipo->ubicacion->nombre ?? 'Sin ubicar' }}</span>
        </div>

        <hr class="mt-4">

        <legend class="w-auto px-2 text-primary"><i class="fas fa-money-bill-wave"></i>Informacion Contable</legend>

        <div class="data-item">
            <span class="data-label">Valor Inicial:</span> 
            <span class="float-right text-success text-bold">${{ number_format($equipo->valor_inicial, 2) }}</span>
        </div>
        <div class="data-item">
            <span class="data-label">F. Adquisicion:</span> 
            <span class="float-right">{{ $equipo->fecha_adquisicion }}</span>
        </div>
        <div class="data-item">
            <span class="data-label">Vida Util Estimada:</span> 
            <span class="float-right">{{ $equipo->vida_util_estimada }}</span>
        </div>

        <hr class="mt-4">
        
        <legend class="w-auto px-2 text-primary">
            <i class="fas fa-puzzle-piece"></i> Componentes Instalados 
            {{-- Sumamos solo los que tienen estado 1 (Activos) --}}
            @php
                $soloActivos = $equipo->perifericos->where('is_active', 1)->count() + 
                $equipo->rams->where('is_active', 1)->count() + 
                $equipo->procesadores->where('is_active', 1)->count() + 
                $equipo->monitores->where('is_active', 1)->count() + 
                $equipo->discosDuros->where('is_active', 1)->count();
            @endphp
            ({{ $soloActivos }})
        </legend>

        {{-- Perifericos Activos --}}
        <h6><i class="fas fa-keyboard text-warning"></i> Perifericos</h6>
        <ul class="list-unstyled mb-3 ml-3">
            @forelse($equipo->perifericos->where('is_active', 1) as $p)
                <li>- {{ $p->tipo }} | Serial: {{ $p->serial }} | {{$p->marca}}</li>
            @empty
                <li class="text-muted">Ninguno activo.</li>
            @endforelse
        </ul>

        {{-- RAM Activas --}}
        <h6><i class="fas fa-memory text-warning"></i> RAM</h6>
        <ul class="list-unstyled mb-3 ml-3">
            @forelse($equipo->rams->where('is_active', 1) as $r)
                <li>- {{ $r->capacidad_gb }}GB | {{ $r->tipo_chz }}</li>
            @empty
                <li class="text-muted">Ninguna activa.</li>
            @endforelse
        </ul>

        {{-- Procesadores Activos --}}
        <h6><i class="fas fa-microchip text-warning"></i> Procesador</h6>
        <ul class="list-unstyled mb-3 ml-3">
            @forelse($equipo->procesadores->where('is_active', 1) as $proc)
                <li>- {{ $proc->marca }} | {{ $proc->descripcion_tipo }}</li>
            @empty
                <li class="text-muted">Ninguno activo.</li>
            @endforelse
        </ul>

        {{-- Monitores Activos --}}
        <h6><i class="fas fa-tv text-warning"></i> Monitores</h6>
        <ul class="list-unstyled mb-3 ml-3">
            @forelse($equipo->monitores->where('is_active', 1) as $mon)
                <li>- {{ $mon->marca }} | Serial: {{ $mon->serial }} | {{ $mon->escala_pulgadas }}" | {{ $mon->interface }} </li>
            @empty
                <li class="text-muted">Ninguno activo.</li>
            @endforelse
        </ul>

        {{-- Discos Activos --}}
        <h6><i class="fas fa-hdd text-warning"></i> Discos</h6>
        <ul class="list-unstyled mb-3 ml-3">
            @forelse($equipo->discosDuros->where('is_active', 1) as $dd)
                <li>- {{ $dd->capacidad }} | {{ $dd->tipo_hdd_ssd }} | {{ $dd->interface }}</li>
            @empty
                <li class="text-muted">Ninguno activo.</li>
            @endforelse
        </ul>
    </div> {{-- /card-body --}}
    </fieldset>
</div> {{-- /card --}}
