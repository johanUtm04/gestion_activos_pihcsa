<div class="card card-outline card-success shadow-sm mb-4">
    <div class="search-header border-0 shadow-none" onclick="togglePanel()" 
         style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: transparent;">
        
        <h3 class="card-title text-success font-weight-bold mb-0">
            <i class="fas fa-search mr-2"></i> Panel de Búsqueda de Usuarios
        </h3>
        
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-success">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top">
            <form action="{{ route('users.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Seleccionar Usuario</label>
                            <select name="usuario_id" class="form-control form-control-sm">
                                <option value="">-- Todos los usuarios --</option>
                                @foreach($todosLosUsuarios as $u)
                                    <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group w-100">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Filtrar
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar búsqueda">
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