<div class="card card-outline card-pink-pure shadow-sm mb-2">
    <div class="search-header border-0 shadow-none"
         onclick="togglePanel()"
         style="cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 15px;
                background: transparent;">

        <h3 class="card-title text-pink-pure font-weight-bold mb-0"
            style="font-size: 1rem;">
            <i class="fas fa-search mr-2"></i>
            Panel de Búsqueda de Áreas
        </h3>

        <div class="card-tools">
            <button type="button"
                    class="btn btn-tool text-pink-pure">
                <i class="fas fa-plus" id="toggle-icon"></i>
            </button>
        </div>
    </div>

    <div class="search-body"
         id="searchBody"
         style="max-height: 0;
                overflow: hidden;
                transition: all 0.4s ease-in-out;
                opacity: 0;">

        <div class="card-body border-top py-2 bg-light">
            <form action="{{ route('empresas.index') }}" method="GET">
                <div class="row align-items-end">

                    {{-- FILTRO POR ÁREA --}}
                    <div class="col-md-4">
                        <div class="form-group mb-1">
                            <label for="nombre"
                                   class="small font-weight-bold text-muted text-uppercase">
                                Área
                            </label>

                            <select name="nombre"
                                    id="nombre"
                                    class="form-control form-control-sm shadow-sm">

                                <option value="">
                                    -- Todas las áreas --
                                </option>

                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->nombre }}"
                                        @selected(request('nombre') == $empresa->nombre)>

                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ESPACIO PARA ALINEACIÓN --}}
                    <div class="col-md-5"></div>

                    {{-- BOTONES --}}
                    <div class="col-md-3 text-right">
                        <div class="form-group mb-1">
                            <div class="btn-group w-100">

                                <button type="submit"
                                        class="btn btn-pink-pure btn-sm shadow-sm">
                                    <i class="fas fa-filter mr-1"></i>
                                    FILTRAR
                                </button>

                                <a href="{{ route('empresas.index') }}"
                                   class="btn btn-default btn-sm shadow-sm"
                                   title="Limpiar filtros">
                                    <i class="fas fa-sync-alt text-pink-pure"></i>
                                </a>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>