@section('content_header')

@php
    $sucursales = config('sucursales.disponibles', []);
    $sucursalActiva = session('sucursal_activa', auth()->user()->sucursal ?? config('sucursales.default'));
    $nombreSucursal = $sucursales[$sucursalActiva] ?? strtoupper($sucursalActiva);

    $sucursalStyles = [
        'morelia' => [
            'bg' => 'linear-gradient(135deg, #e8f8fb 0%, #ffffff 70%)',
            'border' => '#17a2b8',
            'color' => '#138496',
            'icon' => 'fas fa-map-marker-alt',
        ],
        'cdmx' => [
            'bg' => 'linear-gradient(135deg, #f3e8ff 0%, #ffffff 70%)',
            'border' => '#6f42c1',
            'color' => '#6f42c1',
            'icon' => 'fas fa-city',
        ],
        'leon' => [
            'bg' => 'linear-gradient(135deg, #fff3e0 0%, #ffffff 70%)',
            'border' => '#fd7e14',
            'color' => '#e8590c',
            'icon' => 'fas fa-building',
        ],
    ];

    $styleSucursal = $sucursalStyles[$sucursalActiva] ?? [
        'bg' => 'linear-gradient(135deg, #f1f3f5 0%, #ffffff 70%)',
        'border' => '#6c757d',
        'color' => '#495057',
        'icon' => 'fas fa-building',
    ];
@endphp

<div class="branch-header d-flex justify-content-between align-items-center mb-2 py-2 px-3"
     style="
        background: {{ $styleSucursal['bg'] }};
        border-left: 5px solid {{ $styleSucursal['border'] }};
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
     ">

    <div>
        <h4 class="text-dark font-weight-bold mb-1">
            <i class="fas fa-boxes mr-2" style="color: {{ $styleSucursal['color'] }};"></i>
            Inventario de Equipos
        </h4>

        <div class="d-flex align-items-center mt-1 flex-wrap" style="gap: 8px;">

            {{-- ROLE --}}
            <div class="d-flex align-items-center">
                <small class="text-muted mr-2">Rol:</small>
                <span class="badge py-1 px-2"
                      style="border: 1px solid {{ $styleSucursal['border'] }}; color: {{ $styleSucursal['color'] }}; background: #fff; font-size: 0.75rem;">
                    {{ ucfirst(auth()->user()->rol) }}
                </span>
            </div>

            {{-- SUCURSAL ACTIVA --}}
            <div class="d-flex align-items-center">
                <small class="text-muted mr-2">Vista actual:</small>

                @if(auth()->user()->rol === 'ADMIN')
                    <form method="POST" action="{{ route('sucursal.cambiar') }}" class="mb-0">
                        @csrf

                        <select name="sucursal"
                                onchange="this.form.submit()"
                                class="form-control form-control-sm py-0 font-weight-bold"
                                style="
                                    height: 26px;
                                    font-size: 0.75rem;
                                    min-width: 125px;
                                    color: {{ $styleSucursal['color'] }};
                                    border-color: {{ $styleSucursal['border'] }};
                                    background-color: #fff;
                                ">
                            @foreach ($sucursales as $key => $nombre)
                                <option value="{{ $key }}" @selected($sucursalActiva === $key)>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <span class="badge py-1 px-2"
                          style="font-size: 0.75rem; background: {{ $styleSucursal['color'] }}; color: #fff;">
                        <i class="{{ $styleSucursal['icon'] }} mr-1"></i>
                        {{ $nombreSucursal }}
                    </span>
                @endif
            </div>

            {{-- INDICADOR VISUAL --}}
            <span class="badge py-1 px-2"
                  style="font-size: 0.75rem; background: {{ $styleSucursal['color'] }}; color: #fff;">
                <i class="{{ $styleSucursal['icon'] }} mr-1"></i>
                Base activa: {{ $nombreSucursal }}
            </span>

        </div>
    </div>

    @can('crear-equipo')
    <div class="d-flex flex-wrap justify-content-end" style="gap: 5px;"> 
        
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