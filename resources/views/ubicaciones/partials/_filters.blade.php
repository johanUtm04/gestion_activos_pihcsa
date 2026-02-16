<div class="card card-outline card-danger shadow-sm mb-4">
    <div class="search-header border-0 shadow-none" onclick="togglePanel()" 
         style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: transparent;">
        
        <h3 class="card-title text-danger font-weight-bold mb-0">
            <i class="fas fa-search mr-2"></i> Panel de Búsqueda de Ubicaciones
        </h3>
        
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-danger">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top">
            <form action="{{ route('ubicaciones.index') }}" method="GET">
                <div class="row">
                    {{-- Búsqueda por Nombre o Dirección --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Seleccionar Usuario</label>
                            <select name="ubicacion_id" class="form-control form-control-sm">
                                <option value="">-- Todos los usuarios --</option>
                                @foreach($ubicaciones as $u)
                                    <option value="{{ $u->id }}" {{ request('ubicacion_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group w-100">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Filtrar
                                </button>
                                <a href="{{ route('ubicaciones.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar búsqueda">
                                    <i class="fas fa-sync-alt text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>