<div class="card card-outline card-info shadow-sm mb-4">
<div class="search-header border-0 shadow-none" onclick="togglePanel()" 
     style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: transparent;">
    
    <h3 class="card-title text-info font-weight-bold mb-0">
        <i class="fas fa-search mr-2"></i> Panel de Búsqueda
    </h3>
    
    <div class="card-tools">
        <button type="button" class="btn btn-tool text-info">
            <i class="fas fa-plus" id="toggle-icon"></i>
        </button>
    </div>
</div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top">
            <form action="{{ route('equipos.index') }}" method="GET">
                <div class="row">
                    {{-- 1. Buscador por Usuario --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Usuario</label>
                            <select name="usuario_id" class="form-control form-control-sm">
                                <option value="">-- Todos los usuarios --</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 2. Filtro por Ubicacion --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Ubicacion</label>
                            <select name="ubicacion_id" class="form-control form-control-sm">
                                <option value="">-- Todas las sedes --</option>
                                @foreach($ubicaciones as $u)
                                    <option value="{{ $u->id }}" {{ request('ubicacion_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filtro por Tipo de Activo --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Tipo de Activo</label>
                            <select name="tipo_activo_id" class="form-control form-control-sm">
                                <option value="">-- Todas los tipos --</option>
                                @foreach($tipos as $m)
                                    <option value="{{ $m->id }}" {{ request('tipo_activo_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filtro por Marca --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Marca</label>
                            <select name="marca_id" class="form-control form-control-sm">
                                <option value="">-- Todas las marcas --</option>
                                @foreach($marcas as $m)
                                    <option value="{{ $m->id }}" {{ request('marca_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filtro por Monitor --}}
                    <div class="row mb-4 p-3 bg-white" style="border-left: 4px solid #007bff !important;">
                        <div class="col-12 mb-3">
                            <h6 class="text-primary font-weight-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-desktop mr-2"></i> FILTRAR POR MONITORES
                            </h6>
                            <hr class="my-2">
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-muted text-uppercase">
                                    <i class="fas fa-tag mr-1"></i> Marca
                                </label>
                                <select name="monitor_marca" class="form-control form-control-sm">
                                    <option value="">-- Todas las Marcas --</option>
                                    @foreach($marcas_monitores as $marca)
                                        <option value="{{ $marca }}" {{ request('monitor_marca') == $marca ? 'selected' : '' }}>
                                            {{ $marca }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-muted text-uppercase">
                                    <i class="fas fa-expand mr-1"></i> Escala (Pulgadas)
                                </label>
                                <select name="escala_pulgadas" class="form-control form-control-sm">
                                    <option value="">-- Todas las medidas --</option>
                                    @foreach($escalas_pulgadas as $pulgada)
                                        <option value="{{ $pulgada }}" {{ request('escala_pulgadas') == $pulgada ? 'selected' : '' }}>
                                            {{ $pulgada }}"
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-muted text-uppercase">
                                    <i class="fas fa-tag mr-1"></i> Interface
                                </label>
                                <select name="monitor_interface" class="form-control form-control-sm">
                                    <option value="">-- Todas las Interfaces --</option>
                                    @foreach($monitor_interface as $interface)
                                        <option value="{{ $interface }}" {{ request('monitor_interface') == $interface ? 'selected' : '' }}>
                                            {{ $interface }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    {{-- 4. Botones --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-info btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i> Filtrar
                                </button>
                                <a href="{{ route('equipos.index') }}" class="btn btn-default btn-sm shadow-sm" title="Limpiar búsqueda">
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