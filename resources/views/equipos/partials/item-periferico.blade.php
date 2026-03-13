@php
    $estaInactivo = isset($periferico) && !$periferico->is_active;
@endphp

<div class="periferico-item p-3 mb-3 border rounded {{ $estaInactivo ? 'bg-light border-danger opacity-75' : 'bg-light shadow-sm' }} item-componente">
    
    {{-- ENCABEZADO: Siempre visible --}}
    <div class="d-flex justify-content-between align-items-center {{ $estaInactivo ? '' : 'mb-3' }}">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-desktop"></i> Periférico #
            <span class="numero-index badge {{ $estaInactivo ? 'badge-danger' : 'badge-secondary' }}">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
            @if($estaInactivo)
                <span class="badge badge-danger ml-2"><i class="fas fa-exclamation-triangle"></i> INACTIVO (Dado de baja)</span>
            @endif
        </h6>
        
        <div class="btn-group">
            <button type="button" 
                    class="btn btn-sm btn-outline-info mr-2" 
                    data-toggle="collapse" 
                    data-target="#collapsePeriferico-{{ $index }}" 
                    aria-expanded="{{ $estaInactivo ? 'false' : 'true' }}">
                <i class="fas fa-eye"></i> {{ $estaInactivo ? 'Ver detalles' : 'Contraer' }}
            </button>

            <button type="button" 
                    onclick="eliminarComponente(this, 'periferico-item')" 
                    class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    {{-- CUERPO COLAPSABLE --}}
    <div id="collapsePeriferico-{{ $index }}" class="collapse {{ $estaInactivo ? '' : 'show' }} mt-3">
        
        <input type="hidden" name="periferico[{{ $index }}][id]" value="{{ $periferico->id ?? '' }}">
        <input type="hidden" name="periferico[{{ $index }}][_delete]" value="">

        <div class="row">
            {{-- Columna Tipo --}}
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Tipo de periférico</label>
                <select name="periferico[{{ $index }}][tipo]" 
                        class="form-control form-control-sm"
                        data-current="{{ $periferico->tipo ?? '' }}">
                    <option value="">Seleccione...</option>
                    @foreach(['Mouse','Teclado','Combo','Webcam','Audífonos','Otro'] as $t)
                        <option value="{{ $t }}" {{ ($periferico->tipo ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Columna Marca --}}
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Marca</label>
                <select name="periferico[{{ $index }}][marca]" 
                        class="form-control form-control-sm"
                        data-current="{{ $periferico->marca ?? '' }}">
                    <option value="">Seleccione...</option>
                    @foreach(['Logitech','HP','Dell','Lenovo','Microsoft','Genius','Razer','Corsair','HyperX','Redragon','Otra'] as $marca)
                        <option value="{{ $marca }}" {{ ($periferico->marca ?? '') == $marca ? 'selected' : '' }}>{{ $marca }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Columna Serial --}}
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Serial</label>
                <input type="text" name="periferico[{{ $index }}][serial]" 
                       value="{{ $periferico->serial ?? '' }}" 
                       data-current="{{ $periferico->serial ?? '' }}" 
                       class="form-control form-control-sm" placeholder="S/N">
            </div>

            {{-- Columna Interfaz --}}
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Interfaz / Conexión</label>
                <select name="periferico[{{ $index }}][interface]" 
                        class="form-control form-control-sm"
                        data-current="{{ $periferico->interface ?? '' }}">
                    <option value="">Seleccione...</option>
                    <option value="USB" {{ (isset($periferico) && $periferico->interface == 'USB') ? 'selected' : '' }}>USB</option>
                    <option value="Bluetooth" {{ (isset($periferico) && $periferico->interface == 'Bluetooth') ? 'selected' : '' }}>Bluetooth</option>
                </select>
            </div>
        </div>

        {{-- Switch de Estado y Motivo --}}
        <div class="row align-items-center mt-2 border-top pt-2">
            <div class="col-md-4">
                <div class="custom-control custom-switch">
                    {{-- Para los checkboxes, el data-current guarda "1" o "0" --}}
                    <input type="checkbox" 
                           class="custom-control-input switch-estado-componente" 
                           id="switch-perif-{{ $index }}" 
                           name="periferico[{{ $index }}][is_active]" 
                           value="1" 
                           data-current="{{ !isset($periferico) || $periferico->is_active ? '1' : '0' }}"
                           {{ !isset($periferico) || $periferico->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label small font-weight-bold {{ !isset($periferico) || $periferico->is_active ? 'text-success' : 'text-danger' }}" 
                           for="switch-perif-{{ $index }}">
                        {{ !isset($periferico) || $periferico->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                    </label>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="div-motivo" @if(!isset($periferico) || $periferico->is_active) style="display: none;" @endif>
                    @php 
                        $motivoActual = isset($periferico->motivo_inactivo) ? trim(explode('|', $periferico->motivo_inactivo)[0]) : '';
                    @endphp
                    <input type="text" 
                           name="periferico[{{ $index }}][motivo_inactivo]" 
                           class="form-control form-control-sm border-danger input-motivo" 
                           placeholder="Motivo de baja..."
                           value="{{ $motivoActual }}"
                           data-current="{{ $motivoActual }}">
                </div>
            </div>
        </div>
        <small class="text-muted d-block mt-2">ID Sistema: {{ $periferico->id ?? 'Pendiente' }}</small>
    </div>
</div>