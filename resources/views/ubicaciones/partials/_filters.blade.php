<div class="card card-outline card-red-pure shadow-sm mb-2">
    <div class="search-header border-0 shadow-none" onclick="togglePanel()" 
         style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 8px 15px; background: transparent;">
        
        <h3 class="card-title text-red-pure font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-search mr-2"></i> Panel de Búsqueda de Ubicaciones
        </h3>
        
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-red-pure">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top py-2 bg-light">
            <form action="{{ route('ubicaciones.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-muted text-uppercase">Ubicación</label>
                            <select name="ubicacion_id" class="form-control form-control-sm shadow-sm">
                                <option value="">-- Todos --</option>
                                @foreach($todasLasUbicaciones as $u)
                                    <option value="{{ $u->id }}" @selected(request('ubicacion_id') == $u->id)>
                                        {{ $u->nombre }}  ({{ $u->codigo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 text-right">
                        <div class="form-group mb-1">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-red-pure btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> FILTRAR
                                </button>
                                <a href="{{ route('ubicaciones.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar">
                                    <i class="fas fa-sync-alt text-red-pure"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
