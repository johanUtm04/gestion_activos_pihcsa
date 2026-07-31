<div class="card card-outline card-green-pure shadow-sm mb-2">
    <div class="search-header border-0 shadow-none" onclick="togglePanel()" 
         style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 8px 15px; background: transparent;">
        
        <h3 class="card-title text-green-pure font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-search mr-2"></i> Panel de Búsqueda de Usuarios
        </h3>
        
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-green-pure">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top py-2 bg-light">
            <form action="{{ route('users.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-muted text-uppercase">Usuario</label>
                            <select name="usuario_id" class="form-control form-control-sm shadow-sm">
                                <option value="">-- Todos --</option>
                                @foreach($todosLosUsuarios as $u)
                                    <option value="{{ $u->id }}" @selected(request('usuario_id') == $u->id)>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-muted text-uppercase">Rol</label>
                            <select name="rol" class="form-control form-control-sm shadow-sm">
                                <option value="">-- Todos --</option>
                                <option value="admin" @selected(request('rol') == 'admin')>ADMIN</option>
                                <option value="INVITADO" @selected(request('rol') == 'INVITADO')>INVITADO</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-1">
                            <label class="small font-weight-bold text-muted text-uppercase">Departamento</label>
                            <select name="departamento" class="form-control form-control-sm shadow-sm">
                                <option value="">-- Todos --</option>
                                @php
                                    $deps = ['ADMINISTRACION','ALMACEN','CALIDAD','COBRANZA','COMPRAS','CONTABILIDAD','CREDITO','CULTURA Y TALENTO','DIRECCION','EMBARQUES','INVENTARIOS','JURIDICO','LOGISTICA','SISTEMAS','VENTAS','VENTAS_GOB','VENTAS_PRIV'];
                                @endphp
                                @foreach($deps as $d)
                                    <option value="{{ $d }}" @selected(request('departamento') == $d)>{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 text-right">
                        <div class="form-group mb-1">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-green-pure btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> FILTRAR
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar">
                                    <i class="fas fa-sync-alt text-green-pure"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
