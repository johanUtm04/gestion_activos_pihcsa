@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
    <h1 class="text-dark font-weight-bold mb-1">
        <i class="fas fa-boxes text-info mr-2"></i>Inventario de Activos
    </h1>
    <p class="text-muted mb-2">
        Consulta, edición y seguimiento de equipos tecnológicos y periféricos
    </p>
    <div class="d-flex align-items-center">
        <small class="text-secondary mr-2">Actualmente eres:</small>
        <span class="badge badge-outline-info" style="border: 1px solid #17a2b8; color: #17a2b8; background: transparent;">
            <i class="fas fa-user-shield mr-1"></i>{{ ucfirst(auth()->user()->rol) }}
        </span>
    </div>
</div>

    @can('crear-equipo')
    <div class="d-flex" style="gap: 10px;">
        <div class="btn-group shadow-sm">
            @if(request('filter') == 'inactivos')
                <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary font-weight-bold">
                    <i class="fas fa-eye mr-1"></i> Ver Activos
                </a>
            @else
                <a href="{{ route('equipos.index', ['filter' => 'inactivos']) }}" class="btn btn-outline-danger font-weight-bold">
                    <i class="fas fa-trash-restore mr-1"></i> Ver Inactivos
                </a>
            @endif
        </div>

        @if(request('filter') !== 'inactivos')
        <a href="{{ route('equipos.reporte') }}" class="btn btn-outline-success shadow-sm d-flex align-items-center">
            <i class="fas fa-file-excel mr-2"></i> Reporte General
        </a>
        @endif

         @if(request('filter') !== 'inactivos')
        <a href="{{ route('equipos.wizard.create') }}" class="btn btn-info shadow-sm d-flex align-items-center">
            <i class="fas fa-plus-circle mr-2"></i> Nuevo Activo
        </a>
        @endif
    </div>
    @endcan
</div>
@stop