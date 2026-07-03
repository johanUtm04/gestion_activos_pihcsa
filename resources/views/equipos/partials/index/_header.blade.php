@section('content_header')
{{-- Cambié mb-4 por mb-2 y eliminé paddings innecesarios --}}
<div class="d-flex justify-content-between align-items-center mb-1 py-1">
    <div>
        <h4 class="text-dark font-weight-bold mb-0">
            <i class="fas fa-boxes text-info mr-2"></i>Inventario de Equipos
        </h4>
        <div class="d-flex align-items-center mt-1 flex-wrap" style="gap: 8px;">

            {{-- ROLE --}}
            <div class="d-flex align-items-center">
                <small class="text-muted mr-2">Rol:</small>
                <span class="badge badge-outline-info py-0" style="border: 1px solid #17a2b8; color: #17a2b8; font-size: 0.75rem;">
                    {{ ucfirst(auth()->user()->rol) }}
                </span>
            </div>

            @php
                $sucursales = config('sucursales.disponibles', []);
                $sucursalActiva = session('sucursal_activa', auth()->user()->sucursal ?? config('sucursales.default'));
            @endphp

            {{-- BRANCH / SUCURSAL --}}
            <div class="d-flex align-items-center">
                <small class="text-muted mr-2">Sucursal:</small>

                @if(auth()->user()->rol === 'ADMIN')
                    <form method="POST" action="{{ route('sucursal.cambiar') }}" class="mb-0">
                        @csrf

                        <select name="sucursal"
                                onchange="this.form.submit()"
                                class="form-control form-control-sm py-0"
                                style="height: 24px; font-size: 0.75rem; min-width: 110px;">
                            @foreach ($sucursales as $key => $nombre)
                                <option value="{{ $key }}" @selected($sucursalActiva === $key)>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <span class="badge badge-info py-0" style="font-size: 0.75rem;">
                        {{ $sucursales[$sucursalActiva] ?? strtoupper($sucursalActiva) }}
                    </span>
                @endif
            </div>

        </div>
    </div>

    @can('crear-equipo')
    <div class="d-flex" style="gap: 5px;"> 
        
        {{-- BOTÓN AGREGADO: Enlace rápido para saltar al inventario de vehículos --}}
        <a href="{{ route('vehiculos.index') }}" class="btn btn-sm btn-outline-info font-weight-bold shadow-sm" title="Ver Inventario de Vehículos">
            <i class="fas fa-car mr-1"></i> Vehículos
        </a>

        <div class="btn-group shadow-sm">
            @if(request('filter') == 'inactivos')
                <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold">
                    <i class="fas fa-eye"></i> Activos
                </a>
            @else
                <a href="{{ route('equipos.index', ['filter' => 'inactivos']) }}" class="btn btn-sm btn-outline-danger font-weight-bold">
                    <i class="fas fa-trash-restore"></i> Inactivos
                </a>
            @endif
        </div>

        @if(request('filter') !== 'inactivos')
        <a href="{{ route('equipos.reporte') }}" class="btn btn-sm btn-outline-success shadow-sm">
            <i class="fas fa-file-excel"></i> Reporte
        </a>
        @endif

        @if(request('filter') !== 'inactivos')
        <a href="{{ route('equipos.busqueda') }}" class="btn btn-sm btn-outline-secondary shadow-sm" title="Escanear Activo">
            <i class="fas fa-barcode mr-1"></i> Escanear
        </a>

        <a href="{{ route('equipos.wizard.create') }}" class="btn btn-sm btn-info shadow-sm">
            <i class="fas fa-plus-circle"></i> Nuevo
        </a>
        @endif
    </div>
    @endcan
</div>
@stop