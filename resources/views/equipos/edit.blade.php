@extends('adminlte::page')

@section('title', 'Editar Equipo | Activos TI')

@section('css')
<style>
    .section-title { border-bottom: 2px solid #007bff; padding-bottom: 5px; margin-bottom: 15px;color: #17a2b8; font-weight: 600;}

    .data-item { margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px dashed #ced4da;}

    .data-item:last-child {border-bottom: none;}

    .data-label {font-weight: 600;color: #495057;}

    .custom-input {
    display: none;
    margin-top: 10px;
    }

    .component-group {border: 1px solid #dee2e6;border-radius: .25rem;padding: 15px;margin-bottom: 20px;background-color: #f8f9fa;}
</style>
@stop

@section('content_header')
    <h1 class="font-weight-bold text-center">
        <i class="fas fa-desktop text-primary"></i> 
        Edición de Activo: {{ strtoupper($equipo->marca_equipo) }}
    </h1>
    <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver al inventario
    </a>
@stop


@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- COLUMNA IZQUIERDA: DATOS ACTUALES Y RELACIONES (Visibilidad) --}}
            <div class="col-md-5">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list"></i> **Detalle y Estado Actual**
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <h5 class="section-title"><i class="fas fa-cogs"></i> Especificaciones Generales</h5>
                        
                        {{-- Datos Principales --}}
                        <div class="data-item">
                            <span class="data-label">Marca/Modelo:</span> 
                            <span class="float-right">{{ $equipo->marca?->nombre ?? 'Sin Marca' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Tipo de Equipo:</span> 
                            <span class="float-right">{{ $equipo->tipoActivo?->nombre ?? 'Sin Marca' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Serial:</span> 
                            <span class="float-right text-bold">{{ $equipo->serial }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label"><i class="fab fa-windows"></i> S. Operativo:</span> 
                            <span class="float-right">{{ $equipo->sistema_operativo }}</span>
                        </div>
                        
                        <hr class="mt-4">

                        <h5 class="section-title"><i class="fas fa-users-cog"></i> Responsabilidad y Ubicacion</h5>

                        <div class="data-item">
                            <span class="data-label"><i class="fas fa-user-tag"></i> Usuario:</span> 
                            <span class="float-right text-primary">{{ $equipo->usuario->name ?? 'Sin asignar' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label"><i class="fas fa-map-marker-alt"></i> Ubicacion:</span> 
                            <span class="float-right text-primary">{{ $equipo->ubicacion->nombre ?? 'Sin ubicar' }}</span>
                        </div>

                        <hr class="mt-4">

                        <h5 class="section-title"><i class="fas fa-money-bill-wave"></i> Informacion Contable</h5>

                        <div class="data-item">
                            <span class="data-label">Valor Inicial:</span> 
                            <span class="float-right text-success text-bold">${{ number_format($equipo->valor_inicial, 2) }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">F. Adquisicion:</span> 
                            <span class="float-right">{{ $equipo->fecha_adquisicion }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Vida Util Estimada:</span> 
                            <span class="float-right">{{ $equipo->vida_util_estimada }}</span>
                        </div>

                        <hr class="mt-4">

                        <h5 class="section-title"><i class="fas fa-puzzle-piece"></i> Componentes Asociados ({{ $equipo->perifericos->count() + $equipo->rams->count() + $equipo->procesadores->count() + $equipo->monitores->count() + $equipo->discosDuros->count() }})</h5>

                        {{-- Detalle Perifericos --}}
                        <h6><i class="fas fa-keyboard text-warning"></i> **Perifericos ({{ $equipo->perifericos->count() }})**</h6>
                        <ul class="list-unstyled mb-3 ml-3">
                            @forelse($equipo->perifericos as $p)
                                <li>- {{ $p->tipo }} (Serial: {{ $p->serial }})</li>
                            @empty
                                <li class="text-muted">Ninguno.</li>
                            @endforelse
                        </ul>

                        {{-- Detalle RAMs --}}
                        <h6><i class="fas fa-memory text-warning"></i> **RAM ({{ $equipo->rams->count() }})**</h6>
                        <ul class="list-unstyled mb-3 ml-3">
                            @forelse($equipo->rams as $r)
                                <li>- {{ $r->capacidad_gb }}GB | {{ $r->tipo_chz }}</li>
                            @empty
                                <li class="text-muted">Ninguna.</li>
                            @endforelse
                        </ul>
                        
                        {{-- Detalle Procesadores --}}
                        <h6><i class="fas fa-microchip text-warning"></i> **Procesador ({{ $equipo->procesadores->count() }})**</h6>
                        <ul class="list-unstyled mb-3 ml-3">
                            @forelse($equipo->procesadores as $proc)
                                <li>- {{ $proc->marca }} | {{ $proc->descripcion_tipo }}</li>
                            @empty
                                <li class="text-muted">Ninguno.</li>
                            @endforelse
                        </ul>

                        {{-- Detalle Monitores --}}
                        <h6><i class="fas fa-tv text-warning"></i> **Monitores ({{ $equipo->monitores->count() }})**</h6>
                        <ul class="list-unstyled mb-3 ml-3">
                            @forelse($equipo->monitores as $mon)
                                <li>- {{ $mon->marca }} ({{ $mon->escala_pulgadas }}")</li>
                            @empty
                                <li class="text-muted">Ninguno.</li>
                            @endforelse
                        </ul>

                        {{-- Detalle Discos Duros --}}
                        <h6><i class="fas fa-hdd text-warning"></i> **Discos ({{ $equipo->discosDuros->count()}})**</h6>
                        <ul class="list-unstyled mb-3 ml-3">
                            @forelse($equipo->discosDuros as $dd)
                                <li>- {{ $dd->capacidad }} {{ $dd->tipo_hdd_ssd }}</li>
                            @empty
                                <li class="text-muted">Ninguno.</li>
                            @endforelse
                        </ul>

                    </div> {{-- /card-body --}}
                </div> {{-- /card --}}
            </div> {{-- /col-md-5 --}}

            {{-- COLUMNA DERECHA: FORMULARIO DE EDICION (Funcionalidad) --}}
            <div class="col-md-7">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pen-square"></i> **Modificacion de Datos**
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <form action="{{ route('equipos.update', $equipo) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- SECCION DATOS GENERALES EDITABLES --}}
                            <fieldset class="border p-3 mb-4">
                                <legend class="w-auto px-2 text-primary"><i class="fas fa-info-circle"></i> Datos Base</legend>

                                <div class="row">
                                    {{-- MARCA EQUIPO --}}
                                    <div class="form-group col-md-4">
                                        <label for="marca_id"><i class="fas fa-tag"></i> Marca del Equipo</label>
                                        <select name="marca_id" id="marca_id" class="form-control">
                                            <option value="">Seleccione una marca</option>
                                            @foreach($marcas as $item)
                                                    <option value="{{ $item->id }}" {{ $equipo->marca_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nombre }}
                                                    </option>
                                            @endforeach
                                        </select>
                                    </div>  

                                    {{-- TIPO EQUIPO --}}
                                    <div class="form-group col-md-4">
                                        <label for="tipo_activo_id"><i class="fas fa-tag"></i> Tipo del equipo</label>
                                        <select name="tipo_activo_id" id="tipo_activo_id" class="form-control">
                                            <option value="">Seleccione un tipo de Activo</option>
                                            @foreach($tiposActivo as $item)
                                                    <option value="{{ $item->id }}" {{ $equipo->tipo_activo_id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nombre }}
                                                    </option>
                                            @endforeach
                                        </select>
                                    </div>  


                                    {{-- SERIAL DEL EQUIPO --}}
                                    <div class="form-group col-md-4">
                                        <label for="serial"><i class="fas fa-barcode"></i> No. Serial del Equipo</label>
                                        <input 
                                            type="text" 
                                            name="serial" 
                                            id="serial" 
                                            class="form-control @error('serial') is-invalid @enderror" 
                                            placeholder="Ingrese el serial"
                                            value="{{ old('serial', $equipo->serial) }}" 
                                            required
                                            style="text-transform: uppercase;"
                                            onkeyup="this.value = this.value.toUpperCase();">
                                        
                                        @error('serial')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                            </fieldset>

                            {{-- SECCION DATOS ASOCIADOS (Dropdowns) --}}
                            <fieldset class="border p-3 mb-4">
                                <legend class="w-auto px-2 text-primary"><i class="fas fa-link"></i> Asignacion</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="usuario_id"><i class="fas fa-user-tag"></i> Usuario Responsable</label>
                                        <select name="usuario_id" id="usuario_id" class="form-control select2" data-placeholder="Seleccione un usuario">
                                            <option value="">Seleccione...</option>
                                            @foreach($usuarios as $usuario) 
                                            <option value="{{ $usuario->id }}"
                                                {{ $equipo->usuario_id == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="ubicacion_id"><i class="fas fa-map-marker-alt"></i> Ubicacion</label>
                                        <select name="ubicacion_id" id="ubicacion_id" class="form-control select2" data-placeholder="Seleccione la ubicacion">
                                            <option value="">Seleccione...</option>
                                            @foreach($ubicaciones as $ubicacion)
                                            <option value="{{ $ubicacion->id }}"
                                                {{ $equipo->ubicacion_id == $ubicacion->id ? 'selected' : '' }}>
                                                {{ $ubicacion->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="valor_inicial"><i class="fas fa-dollar-sign"></i> Valor Inicial</label>
                                        <input type="number" name="valor_inicial" id="valor_inicial" class="form-control" step="0.01"
                                            value="{{ old('valor_inicial', $equipo->valor_inicial) }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="fecha_adquisicion"><i class="fas fa-calendar-alt"></i> Fecha de Adquisicion</label>
                                        <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" class="form-control"
                                            value="{{ old('fecha_adquisicion', $equipo->fecha_adquisicion) }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="vida_util_estimada"><i class="fas fa-hourglass-half"></i> Vida Util Estimada</label>
                                        <div class="input-group">
                                            <select class="form-control" name="vida_util_unidad" id="vida_util_unidad">
                                                @php
                                                    $unidadActual = old('vida_util_unidad', $equipo->vida_util_unidad ?? '');
                                                @endphp
                                                <option value="" disabled {{ $unidadActual == '' ? 'selected' : '' }}>Unidad</option>
                                                <option value="años" {{ $unidadActual == 'años' ? 'selected' : '' }}>Años</option>
                                                <option value="meses" {{ $unidadActual == 'meses' ? 'selected' : '' }}>Meses</option>
                                            </select>

                                            <input 
                                                type="number" 
                                                name="vida_util_estimada" 
                                                id="vida_util_input"
                                                class="form-control" 
                                                style="width: 50%;"
                                                placeholder="Cantidad"
                                                min="1"
                                                value="{{ old('vida_util_estimada', $equipo->vida_util_estimada) }}" 
                                                {{ $unidadActual ? '' : 'disabled' }}
                                                >
                                        </div>
                                    </div>
                                </div>
                            </fieldset>


                            {{-- SECCIÓN COMPONENTES EDITABLES --}}
                            <h5 class="section-title mt-4"><i class="fas fa-tools"></i> Edicion Detallada de Componentes</h5>

                                <div id="componentes-editables">

                                    {{-- Perifericos --}}
                                    <div class="component-group bg-light border-left border-info p-3 mb-4 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-info font-weight-bold mb-0">
                                                <i class="fas fa-keyboard mr-2"></i> Perifericos
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-info" onclick="agregarComponente('periferico')">
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
                                    <div class="component-group bg-white border-left border-warning p-3 mb-4 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-warning font-weight-bold mb-0">
                                                <i class="fas fa-memory mr-2"></i> RAMs
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="agregarComponente('ram')">
                                                <i class="fas fa-plus-circle"></i> Agregar RAM
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
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-danger font-weight-bold mb-0">
                                                <i class="fas fa-microchip mr-2"></i> Procesadores
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="agregarComponente('procesador')">
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
                                    <div class="component-group bg-white border-left border-success p-3 mb-4 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-success font-weight-bold mb-0">
                                                <i class="fas fa-tv mr-2"></i> Monitores
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-success" onclick="agregarComponente('monitor')">
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
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-primary font-weight-bold mb-0">
                                                <i class="fas fa-hdd mr-2"></i> Almacenamiento (Discos Duros)
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="agregarComponente('discoDuro')">
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

                                {{-- BOTON FINAL (FUERA DE TODO COMPONENT-GROUP) --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-database"></i> Aplicar Cambios y Registrar Historial
                                    </button>
                                </div>
                        </form>
                    </div> {{-- /card-body --}}
                </div> {{-- /card --}}
            </div> {{-- /col-md-7 --}}
        </div> {{-- row --}}
    </div> {{-- container --}}

@stop
@section('js')
    <script src="{{ asset('js/equipos/edit-equipos.js') }}"></script>
    
    <script>
        // Esta funcion debe estar fuera del ready para que sea accesible globalmente
        function toggleOtro(selectElement, containerId) {
            const container = document.getElementById(containerId);
            const input = container.querySelector('input');
            
            if (selectElement.value === 'Otra' || selectElement.value === 'Otro') {
                container.style.display = 'block';
                input.setAttribute('required', 'required');
                input.focus();
            } else {
                container.style.display = 'none';
                input.removeAttribute('required');
                input.value = '';
            }
        }

        $(document).ready(function() {
            // --- 1. Inicialización de Select2 ---
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }

            // --- 2. Lógica de Vida Útil ---
            function checkVidaUtilStatus() {
                const unidadSelect = $('#vida_util_unidad');
                const cantidadInput = $('#vida_util_input');

                // Si el valor es nulo, vacío o el "disabled" por defecto
                if (!unidadSelect.val()) {
                    cantidadInput.prop('disabled', true);
                } else {
                    cantidadInput.prop('disabled', false);
                }
            }

            // Ejecutar al cargar y al cambiar
            checkVidaUtilStatus();
            $('#vida_util_unidad').on('change', checkVidaUtilStatus);
        });


        // Logica para los Switches de Estado de Componentes
        $(document).on('change', '.switch-estado-componente', function() {
            const isChecked = $(this).is(':checked');
            const container = $(this).closest('.item-componente');
            const divMotivo = container.find('.div-motivo');
            const inputMotivo = container.find('.input-motivo');
            const label = $(this).siblings('.custom-control-label');

            if (isChecked) {
                // ACTIVAR
                divMotivo.fadeOut(200);
                inputMotivo.prop('required', false).val('');
                label.text('Activo').removeClass('text-danger').addClass('text-success');
                container.removeClass('bg-gray-light border-danger').addClass('border-success');
            } else {
                // INACTIVAR
                divMotivo.fadeIn(200);
                inputMotivo.prop('required', true).focus();
                label.text('Inactivo').removeClass('text-success').addClass('text-danger');
                container.removeClass('border-success').addClass('bg-gray-light border-danger');
            }
        });
    </script>
@stop