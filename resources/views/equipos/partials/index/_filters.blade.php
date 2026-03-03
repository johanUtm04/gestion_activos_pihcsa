<div class="card card-outline card-info shadow-sm mb-4">
    <div class="search-header border-0 shadow-none" onclick="togglePanel()" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: transparent;">
        <h3 class="card-title text-info font-weight-bold mb-0">
            <i class="fas fa-search mr-2"></i> Panel de Búsqueda Avanzada
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool text-info">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body" id="searchBody" style="max-height: 0; overflow: hidden; transition: all 0.4s ease-in-out; opacity: 0;">
        <div class="card-body border-top bg-light">
            <form action="{{ route('equipos.index') }}" method="GET">
                
        <div class="row mb-4">
            <div class="col-md">
                <label class="small font-weight-bold text-muted text-uppercase">Usuario</label>
                <select name="usuario_id" class="form-control form-control-sm shadow-sm">
                    <option value="">-- Todos --</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" @selected(request('usuario_id') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <label class="small font-weight-bold text-muted text-uppercase">Ubicación</label>
                <select name="ubicacion_id" class="form-control form-control-sm shadow-sm">
                    <option value="">-- Todas --</option>
                    @foreach($ubicaciones as $u)
                        <option value="{{ $u->id }}" @selected(request('ubicacion_id') == $u->id)>{{ $u->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md">
                <label class="small font-weight-bold text-muted text-uppercase">Tipo Activo</label>
                <select name="tipo_activo_id" class="form-control form-control-sm shadow-sm">
                    <option value="">-- Todos --</option>
                    @foreach($tipos as $t)
                        <option value="{{ $t->id }}" @selected(request('tipo_activo_id') == $t->id)>{{ $t->nombre }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md">
                <label class="small font-weight-bold text-muted text-uppercase">Departamento</label>
                <select name="departamento" class="form-control form-control-sm shadow-sm">
                    <option value="">-- Todos --</option>
                    @php
                        $departamentos = [
                            'JURIDICO',
                            'SISTEMAS',
                            'EMBARQUES',
                            'COMPRAS',
                            'VENTAS',
                            'CREDITO',
                            'COBRANZA',
                            'ADMINISTRACION',
                            'CULTURA Y TALENTO',
                            'CALIDAD',
                            'ALMACEN',
                            'CONTABILIDAD'
                        ];
                    @endphp
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep }}" @selected(request('departamento') == $dep)>
                            {{ $dep }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md">
                <label class="small font-weight-bold text-muted text-uppercase">Marca Equipo</label>
                <select name="marca_id" class="form-control form-control-sm shadow-sm">
                    <option value="">-- Todas --</option>
                    @foreach($marcas as $m)
                        <option value="{{ $m->id }}" @selected(request('marca_id') == $m->id)>{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-white border rounded shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
                            <h6 class="text-success font-weight-bold small mb-3"><i class="fas fa-desktop mr-2"></i> MONITORES</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">MARCA</label>
                                    <select name="monitor_marca" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($marcas_monitores as $marca)
                                            @if(!empty(trim($marca)))
                                                <option value="{{ $marca }}" @selected(request('monitor_marca') == $marca)>{{ $marca }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">PULGADAS</label>
                                    <select name="escala_pulgadas" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($escalas_pulgadas as $p)
                                            @if(!empty(trim($p)))
                                                <option value="{{ $p }}" @selected(request('escala_pulgadas') == $p)>{{ $p }}"</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">INTERFACE</label>
                                    <select name="monitor_interface" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($monitor_interface as $i)
                                            @if(!empty(trim($i)))
                                                <option value="{{ $i }}" @selected(request('monitor_interface') == $i)>{{ $i }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-white border rounded shadow-sm h-100" style="border-left: 4px solid #007bff !important;">
                            <h6 class="text-primary font-weight-bold small mb-3"><i class="fas fa-hdd mr-2"></i> ALMACENAMIENTO</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">CAPACIDAD</label>
                                    <select name="disco_capacidad" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($discos_capacidades as $c)
                                            @if(!empty(trim($c)))
                                                <option value="{{ $c }}" @selected(request('disco_capacidad') == $c)>{{ $c }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">TIPO</label>
                                    <select name="disco_tipo" class="form-control form-control-sm">
                                        <option value="">-- Todos --</option>
                                        @foreach($discos_tipos as $t)
                                            @if(!empty(trim($t)))
                                                <option value="{{ $t }}" @selected(request('disco_tipo') == $t)>{{ $t }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">INTERFACE</label>
                                    <select name="disco_interface" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($discos_interfaces as $i)
                                            @if(!empty(trim($i)))
                                                <option value="{{ $i }}" @selected(request('disco_interface') == $i)>{{ $i }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded shadow-sm h-100" style="border-left: 4px solid #ffc30f !important;">
                            <h6 class="text-warning font-weight-bold small mb-3"><i class="fas fa-memory mr-2"></i> MEMORIA RAM</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">CAPACIDAD</label>
                                    <select name="ram_capacidad" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($rams_capacidades as $c)
                                            @if(!empty(trim($c)))
                                                <option value="{{ $c }}" @selected(request('ram_capacidad') == $c)>{{ $c }} GB</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">RELOJ</label>
                                    <select name="ram_clock" class="form-control form-control-sm">
                                        <option value="">-- Todos --</option>
                                        @foreach($rams_clocks as $clock)
                                            @if(!empty(trim($clock)))
                                                <option value="{{ $clock }}" @selected(request('ram_clock') == $clock)>{{ $clock }} MHz</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="x-small font-weight-bold text-muted">TIPO</label>
                                    <select name="ram_tipo" class="form-control form-control-sm">
                                        <option value="">-- Todos --</option>
                                        @foreach($rams_tipos as $t)
                                            @if(!empty(trim($t)))
                                                <option value="{{ $t }}" @selected(request('ram_tipo') == $t)>{{ $t }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-white border rounded shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                            <h6 class="text-danger font-weight-bold small mb-3"><i class="fas fa-microchip mr-2"></i> PROCESADOR</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="x-small font-weight-bold text-muted">MARCA</label>
                                    <select name="procesador_marca" class="form-control form-control-sm">
                                        <option value="">-- Todas --</option>
                                        @foreach($procesador_marcas as $marca)
                                            @if(!empty(trim($marca)))
                                                <option value="{{ $marca }}" @selected(request('procesador_marca') == $marca)>{{ $marca }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="x-small font-weight-bold text-muted">MODELO</label>
                                    <select name="procesador_tipo" class="form-control form-control-sm">
                                        <option value="">-- Todos --</option>
                                        @foreach($procesador_tipos as $tipo)
                                            @if(!empty(trim($tipo)))
                                                <option value="{{ $tipo }}" @selected(request('procesador_tipo') == $tipo)>{{ $tipo }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('equipos.index') }}" class="btn btn-outline-danger shadow-sm mr-2">
                            <i class="fas fa-sync-alt mr-1"></i> RESETEAR FILTROS
                        </a>
                        <button type="submit" class="btn btn-info shadow-sm px-4">
                            <i class="fas fa-filter mr-1"></i> APLICAR BÚSQUEDA
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>