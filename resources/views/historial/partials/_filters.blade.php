<div class="card card-outline card-info shadow-sm mb-4">
    <div class="card-header">
        <h3 class="card-title text-info font-weight-bold">
            <i class="fas fa-filter mr-1"></i> Panel de Búsqueda
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus text-info"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('historial.index') }}" method="GET">
            <div class="row align-items-end">
                {{-- Filtro por Usuario --}}
                <div class="col-md-4">
                    <div class="form-group mb-md-0">
                        <label class="small font-weight-bold text-muted text-uppercase">
                            <i class="fas fa-user-tie mr-1 text-info"></i> Dueño del Activo
                        </label>
                        <select name="usuario_id" class="form-control form-control-sm select2 shadow-none border-info">
                            <option value="">-- Todos los usuarios --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            
                {{-- Filtro por ID del equipo --}}
                <div class="col-md-4">
                    <div class="form-group mb-md-0">
                        <label class="small font-weight-bold text-muted text-uppercase">
                            <i class="fas fa-tag mr-1 text-info"></i> ID Específico (Equipo)
                        </label>
                        <select name="equipo_id" class="form-control form-control-sm select2 border-info">
                            <option value="">-- Todos los IDs --</option>
                            @foreach($listaParaFiltro as $item)
                                <option value="{{ $item->id }}" {{ request('equipo_id') == $item->id ? 'selected' : '' }}>
                                    #{{ $item->id }} - {{ $item->tipoActivo->nombre ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Botones de Acción --}}
                <div class="col-md-4">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-info btn-sm shadow-sm font-weight-bold flex-grow-1 mr-2">
                            <i class="fas fa-search mr-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('historial.index') }}" 
                           class="btn btn-outline-secondary btn-sm shadow-sm border" 
                           title="Limpiar búsqueda e historial">
                            <i class="fas fa-undo-alt text-danger"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>