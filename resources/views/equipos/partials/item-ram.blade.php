@php
    $estaInactivo = isset($ram) && !$ram->is_active;
@endphp

<div class="ram-item p-3 mb-3 border rounded {{ $estaInactivo ? 'bg-light border-danger opacity-75' : 'bg-light shadow-sm' }} item-componente">
    
    {{-- ENCABEZADO COMPACTO --}}
    <div class="d-flex justify-content-between align-items-center {{ $estaInactivo ? '' : 'mb-3' }}">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-memory"></i> Rams #
            <span class="numero-index badge {{ $estaInactivo ? 'badge-danger' : 'badge-secondary' }}">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
            @if($estaInactivo)
                <span class="badge badge-danger ml-2"><i class="fas fa-ban"></i> INACTIVA</span>
            @endif
        </h6>

        <div class="btn-group">
            <button type="button" 
                    class="btn btn-sm btn-outline-info mr-2" 
                    data-toggle="collapse" 
                    data-target="#collapseRam-{{ $index }}" 
                    aria-expanded="{{ $estaInactivo ? 'false' : 'true' }}">
                <i class="fas fa-eye"></i> {{ $estaInactivo ? 'Ver detalles' : 'Contraer' }}
            </button>
            {{-- Si tienes función para eliminar RAM, puedes poner el botón aquí --}}
        </div>
    </div>

    {{-- CUERPO COLAPSABLE --}}
    <div id="collapseRam-{{ $index }}" class="collapse {{ $estaInactivo ? '' : 'show' }} mt-3">
        
        <input type="hidden" name="ram[{{ $index }}][id]" value="{{ $ram->id ?? '' }}">
        <input type="hidden" name="ram[{{ $index }}][_delete]" value="">

        <div class="row">
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Capacidad (GB)</label>
                <select name="ram[{{ $index }}][capacidad_gb]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach([1, 2, 3, 4, 6, 8, 12, 16, 24, 32, 48, 64, 96, 128] as $cap)
                        <option value="{{ $cap }}" {{ ($ram->capacidad_gb ?? '') == $cap ? 'selected' : '' }}>{{ $cap }} GB</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Clock (MHz)</label>
                <select name="ram[{{ $index }}][clock_mhz]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach([1600, 2133, 2400, 2666, 3000, 3200, 3600, 4800, 5200, 5600, 6000] as $freq)
                        <option value="{{ $freq }}" {{ ($ram->clock_mhz ?? '') == $freq ? 'selected' : '' }}>{{ $freq }} MHz</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Tipo (DDR)</label>
                <select name="ram[{{ $index }}][tipo_chz]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach(['DDR2','DDR3','DDR3L','DDR4','DDR4L','DDR5','LPDDR3','LPDDR4','LPDDR4X','LPDDR5','LPDDR5X'] as $tipo)
                        <option value="{{ $tipo }}" {{ ($ram->tipo_chz ?? '') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3"> 
                <label class="small font-weight-bold"><i class="fas fa-barcode"></i> Serial</label>
                <input type="text" 
                    name="ram[{{ $index }}][serial]" 
                    class="form-control form-control-sm" 
                    placeholder="S/N" 
                    value="{{ $ram->serial ?? '' }}">
            </div>
        </div>

        {{-- Estado y Motivo --}}
        <div class="row align-items-center mt-2 border-top pt-2">
            <div class="col-md-4">
                <div class="custom-control custom-switch">
                    <input type="checkbox" 
                        class="custom-control-input switch-estado-componente" 
                        id="switch-ram-{{ $index }}" 
                        name="ram[{{ $index }}][is_active]" 
                        value="1" 
                        {{ !isset($ram) || $ram->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label small font-weight-bold {{ !isset($ram) || $ram->is_active ? 'text-success' : 'text-danger' }}" 
                        for="switch-ram-{{ $index }}">
                        {{ !isset($ram) || $ram->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                    </label>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="div-motivo" style="{{ !isset($ram) || $ram->is_active ? 'display: none;' : '' }}">
                    <input type="text" 
                        name="ram[{{ $index }}][motivo_inactivo]" 
                        class="form-control form-control-sm border-danger input-motivo" 
                        placeholder="Motivo de baja..."
                        value="{{ isset($ram->motivo_inactivo) ? trim(explode('|', $ram->motivo_inactivo)[0]) : '' }}"
                        {{ isset($ram) && !$ram->is_active ? 'required' : '' }}>

                    @if(isset($ram->motivo_inactivo) && strpos($ram->motivo_inactivo, '|') !== false)
                        <span class="badge badge-secondary mt-1">
                            <i class="fas fa-calendar-alt"></i> {{ trim(explode('|', $ram->motivo_inactivo)[1]) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <small class="text-muted d-block mt-2">ID Sistema: {{ $ram->id ?? 'Pendiente' }}</small>
    </div>
</div>