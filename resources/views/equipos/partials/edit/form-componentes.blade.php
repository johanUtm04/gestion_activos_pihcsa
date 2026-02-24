<div class="card card-outline card-info">
<fieldset class="border p-3 mb-4">


<legend class="w-auto px-2 text-primary"><i class="fas fa-tools"></i> Componentes Extra</legend>


<div id="componentes-editables">

    {{-- Perifericos --}}
    <div class="component-group bg-light border-left border-info p-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 header-dinamico">
            <div class="marquee-wrapper">
                <div class="marquee-track">
                    <i class="fas fa-keyboard"></i>
                    <i class="fas fa-mouse"></i>
                    <i class="fas fa-print"></i>
                    <i class="fas fa-headset"></i>
                    <i class="fas fa-scanner"></i>
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-hdd"></i>
                    <i class="fas fa-keyboard"></i>
                    <i class="fas fa-mouse"></i>
                    <i class="fas fa-print"></i>
                    <i class="fas fa-headset"></i>
                    <i class="fas fa-scanner"></i>
                </div>
                <h5 class="text-info font-weight-bold mb-0 title-overlay">
                    <i class="fas fa-keyboard mr-2"></i> Periferico(s)
                </h5>
            </div>

        <button type="button" class="btn btn-sm btn-info shadow-sm" onclick="confirmarAgregar('periferico', 'Periférico')">
            <i class="fas fa-plus-circle"></i> Agregar Periferico
        </button>
        </div>

        <div id="periferico-container" data-count="{{ $equipo->perifericos->count() }}">
            @foreach($equipo->perifericos as $index => $periferico)
                @include('equipos.partials.item-periferico', ['index' => $index, 'periferico' => $periferico])
            @endforeach
        </div>

        <template id="template-periferico">
            @include('equipos.partials.item-periferico', ['index' => '__INDEX__', 'periferico' => null])
        </template>
    </div>

    {{-- RAMs --}}
    <div class="component-group bg-light border-left border-warning p-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 header-dinamico">
            <div class="marquee-wrapper wrapper-warning">
                <div class="marquee-track track-warning">
                    <i class="fas fa-memory"></i>
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-bolt"></i>
                    <i class="fas fa-server"></i>
                    <i class="fas fa-memory"></i>
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-bolt"></i>
                    <i class="fas fa-server"></i>
                    <i class="fas fa-memory"></i>
                    <i class="fas fa-microchip"></i>
                </div>
                <h5 class="text-warning font-weight-bold mb-0 title-overlay">
                    <i class="fas fa-memory mr-2"></i> RAM(S)
                </h5>
            </div>

        <button type="button" 
                class="btn btn-sm btn-warning shadow-sm font-weight-bold" 
                onclick="confirmarAgregar('ram', 'Ram')">
            <i class="fas fa-plus-circle"></i> Agregar Ram
        </button>
        </div>

        <div id="ram-container" data-count="{{ $equipo->rams->count() }}">
            @foreach($equipo->rams as $index => $ram)
                @include('equipos.partials.item-ram', ['index' => $index, 'ram' => $ram])
            @endforeach
        </div>

        <template id="template-ram">
            @include('equipos.partials.item-ram', ['index' => '__INDEX__', 'ram' => null])
        </template>
    </div>

    {{-- Procesadores --}}
    <div class="component-group bg-light border-left border-danger p-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 header-dinamico">
            <div class="marquee-wrapper wrapper-danger">
                <div class="marquee-track track-danger">
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-brain"></i>
                    <i class="fas fa-bolt"></i>
                    <i class="fas fa-atom"></i>
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-microchip"></i>
                    <i class="fas fa-brain"></i>
                    <i class="fas fa-bolt"></i>
                    <i class="fas fa-atom"></i>
                </div>
                <h5 class="text-danger font-weight-bold mb-0 title-overlay">
                    <i class="fas fa-microchip mr-2"></i> Procesador(es)
                </h5>
            </div>

            <button type="button" class="btn btn-sm btn-danger shadow-sm font-weight-bold" onclick="confirmarAgregar('procesador', 'Procesador')">
                <i class="fas fa-plus-circle"></i> Agregar Procesador
            </button>
        </div>

        <div id="procesador-container" data-count="{{ $equipo->procesadores->count() }}">
            @foreach($equipo->procesadores as $index => $procesador)
                @include('equipos.partials.item-procesador', ['index' => $index, 'procesador' => $procesador])
            @endforeach
        </div>

        <template id="template-procesador">
            @include('equipos.partials.item-procesador', ['index' => '__INDEX__', 'procesador' => null])
        </template>
    </div>

    {{-- Monitores --}}
    <div class="component-group bg-light border-left border-success p-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 header-dinamico">
            <div class="marquee-wrapper wrapper-success">
                <div class="marquee-track track-success">
                    <i class="fas fa-desktop"></i>
                    <i class="fas fa-tv"></i>
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-waveform"></i>
                    <i class="fas fa-display"></i>
                    <i class="fas fa-desktop"></i>
                    <i class="fas fa-tv"></i>
                    <i class="fas fa-eye"></i>
                    <i class="fas fa-waveform"></i>
                    <i class="fas fa-display"></i>
                </div>
                <h5 class="text-success font-weight-bold mb-0 title-overlay">
                    <i class="fas fa-tv mr-2"></i> Monitor(es)
                </h5>
            </div>

            <button type="button" class="btn btn-sm btn-success shadow-sm font-weight-bold" onclick="confirmarAgregar('monitor', 'Monitor')">
                <i class="fas fa-plus-circle"></i> Agregar Monitor
            </button>
        </div>

        <div id="monitor-container" data-count="{{ $equipo->monitores->count() }}">
            @foreach($equipo->monitores as $index => $monitor)
                @include('equipos.partials.item-monitor', ['index' => $index, 'monitor' => $monitor])
            @endforeach
        </div>

        <template id="template-monitor">
            @include('equipos.partials.item-monitor', ['index' => '__INDEX__', 'monitor' => null])
        </template>
    </div>

    {{-- Discos Duros --}}
    <div class="component-group bg-light border-left border-primary p-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 header-dinamico">
            <div class="marquee-wrapper wrapper-primary">
                <div class="marquee-track track-primary">
                    <i class="fas fa-hdd"></i>
                    <i class="fas fa-database"></i>
                    <i class="fas fa-save"></i>
                    <i class="fas fa-server"></i>
                    <i class="fas fa-compact-disc"></i>
                    <i class="fas fa-hdd"></i>
                    <i class="fas fa-database"></i>
                    <i class="fas fa-save"></i>
                    <i class="fas fa-server"></i>
                    <i class="fas fa-compact-disc"></i>
                </div>
                <h5 class="text-primary font-weight-bold mb-0 title-overlay">
                    <i class="fas fa-hdd mr-2"></i> Almacenamiento (Discos Duros)
                </h5>
            </div>

        <button type="button" 
                class="btn btn-sm btn-primary shadow-sm font-weight-bold" 
                onclick="confirmarAgregar('discoDuro', 'Disco Duro')">
            <i class="fas fa-plus-circle"></i> Agregar Disco
        </button>
        </div>

        <div id="discoDuro-container" data-count="{{ $equipo->discosDuros->count() }}">
            @foreach($equipo->discosDuros as $index => $discoDuro)
                @include('equipos.partials.item-disco', ['index' => $index, 'discoDuro' => $discoDuro])
            @endforeach
        </div>

        <template id="template-discoDuro">
            @include('equipos.partials.item-disco', ['index' => '__INDEX__', 'discoDuro' => null])
        </template>
    </div>
</div>
</fieldset>
</div> 