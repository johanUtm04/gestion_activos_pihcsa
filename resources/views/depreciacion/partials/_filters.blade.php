<div class="card card-outline card-secondary shadow-sm mb-2"> {{-- Reducido de mb-4 a mb-2 --}}
    <div class="search-header border-0 shadow-none" onclick="togglePanel()" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 8px 15px; background: transparent;">
        <h3 class="card-title text-secondary font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-search mr-2"></i> Panel de Búsqueda Avanzada
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-secondary">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top bg-light py-2"> {{-- py-2 reduce el espacio vertical interno --}}
            <form action="{{ route('depreciacion.index') }}" method="GET">
                <div class="row mb-2"> {{-- Reducido de mb-4 a mb-2 --}}
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Usuario</label>
                        <select name="usuario_id" class="form-control form-control-sm shadow-sm">
                            <option value="">-- Todos --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" @selected(request('usuario_id') == $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Ubicación</label>
                        <select name="ubicacion_id" class="form-control form-control-sm shadow-sm">
                            <option value="">-- Todas --</option>
                            @foreach($ubicaciones as $u)
                                <option value="{{ $u->id }}" @selected(request('ubicacion_id') == $u->id)>{{ $u->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Tipo Activo</label>
                        <select name="tipo_activo_id" class="form-control form-control-sm shadow-sm">
                            <option value="">-- Todos --</option>
                            @foreach($tipos as $t)
                                <option value="{{ $t->id }}" @selected(request('tipo_activo_id') == $t->id)>{{ $t->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted text-uppercase mb-1">Departamento</label>
                        <select name="departamento" class="form-control form-control-sm shadow-sm">
                            <option value="">-- Todos --</option>
                            @php
                                $departamentos = ['ADMINISTRACION','ALMACEN','CALIDAD','COBRANZA','COMPRAS','CONTABILIDAD','CREDITO','CULTURA Y TALENTO','DIRECCION','EMBARQUES','INVENTARIOS','JURIDICO','LOGISTICA','SISTEMAS','VENTAS'];
                            @endphp
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep }}" @selected(request('departamento') == $dep)>{{ $dep }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-end pb-1"> {{-- pb-1 para ajustar el cierre --}}
                        <a href="{{ route('depreciacion.index') }}" class="btn btn-sm btn-outline-danger shadow-sm mr-2">
                            <i class="fas fa-sync-alt mr-1"></i> RESETEAR
                        </a>
                        <button type="submit" class="btn btn-sm btn-info shadow-sm px-4">
                            <i class="fas fa-filter mr-1"></i> FILTRAR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>