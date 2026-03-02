    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top">
            <form action="{{ route('tipo_activos.index') }}" method="GET">
                <div class="row">
                    {{-- Búsqueda por Nombre del Tipo --}}
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Nombre del Tipo de Activo</label>
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Ej: Monitor, Switch, Licencia..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group w-100">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Filtrar
                                </button>
                                <a href="{{ route('tipo_activos.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar">
                                    <i class="fas fa-sync-alt text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
