@php
    $estaInactivo = isset($discoDuro) && !$discoDuro->is_active;
@endphp

<div class="discoDuro-item p-3 mb-3 border rounded {{ $estaInactivo ? 'bg-light border-danger opacity-75' : 'bg-light shadow-sm' }} item-componente">
    
    {{-- ENCABEZADO COMPACTO --}}
    <div class="d-flex justify-content-between align-items-center {{ $estaInactivo ? '' : 'mb-3' }}">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-hdd"></i> Disco Duro #
            <span class="numero-index badge {{ $estaInactivo ? 'badge-danger' : 'badge-secondary' }}">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
            @if($estaInactivo)
                <span class="badge badge-danger ml-2"><i class="fas fa-exclamation-triangle"></i> INACTIVO</span>
            @endif
        </h6>

        <div class="btn-group">
            <button type="button" 
                    class="btn btn-sm btn-outline-info mr-2" 
                    data-toggle="collapse" 
                    data-target="#collapseDisco-{{ $index }}" 
                    aria-expanded="{{ $estaInactivo ? 'false' : 'true' }}">
                <i class="fas fa-eye"></i> {{ $estaInactivo ? 'Ver detalles' : 'Contraer' }}
            </button>
            <button type="button" 
                    onclick="eliminarComponente(this, 'discoDuro-item')" 
                    class="btn btn-sm btn-outline-danger">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    {{-- CUERPO COLAPSABLE --}}
    <div id="collapseDisco-{{ $index }}" class="collapse {{ $estaInactivo ? '' : 'show' }} mt-3">
        
        <input type="hidden" name="discoDuro[{{ $index }}][id]" value="{{ $discoDuro->id ?? '' }}">
        <input type="hidden" name="discoDuro[{{ $index }}][_delete]" value="">

        <div class="row">
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Capacidad</label>
                <select name="discoDuro[{{$index}}][capacidad]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach(['120GB','128GB','240GB','256GB','480GB','500GB','512GB','1TB','2TB','3TB','4TB','6TB','8TB','10TB','12TB','16TB'] as $cap)
                        <option value="{{ $cap }}" {{ ($discoDuro->capacidad ?? '') == $cap ? 'selected' : '' }}>{{ $cap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Tipo</label>
                <select name="discoDuro[{{$index}}][tipo_hdd_ssd]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach(['HDD','SSD','SATA SSD','M.2 SATA','M.2 NVMe','PCIe NVMe','Hybrid SSHD','External HDD','External SSD','Otro'] as $tipo)
                        <option value="{{ $tipo }}" {{ ($discoDuro->tipo_hdd_ssd ?? '') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Interface</label>
                <select name="discoDuro[{{$index}}][interface]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach(['SATA','SATA III','NVMe','PCIe NVMe','M.2 NVMe','USB','USB 3.0','USB-C','Thunderbolt','SAS','eSATA','Otro'] as $inter)
                        <option value="{{ $inter }}" {{ trim(strtoupper($discoDuro->interface ?? '')) === strtoupper($inter) ? 'selected' : '' }}>{{ $inter }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="small font-weight-bold"><i class="fas fa-barcode"></i> Serial</label>
                <input type="text" 
                    name="discoDuro[{{ $index }}][serial]" 
                    class="form-control form-control-sm" 
                    placeholder="S/N" 
                    value="{{ $discoDuro->serial ?? '' }}">
            </div>
        </div>

        {{-- Switch de Estado y Motivo --}}
        <div class="row align-items-center mt-2 border-top pt-2">
            <div class="col-md-4">
                <div class="custom-control custom-switch">
                    <input type="checkbox" 
                           class="custom-control-input switch-estado-componente" 
                           id="switch-disc-{{ $index }}" 
                           name="discoDuro[{{ $index }}][is_active]" 
                           value="1" 
                           {{ !isset($discoDuro) || $discoDuro->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label small font-weight-bold {{ !isset($discoDuro) || $discoDuro->is_active ? 'text-success' : 'text-danger' }}" 
                           for="switch-disc-{{ $index }}">
                        {{ !isset($discoDuro) || $discoDuro->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                    </label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="div-motivo" @if(!isset($discoDuro) || $discoDuro->is_active) style="display: none;" @endif>
                    <input type="text" 
                           name="discoDuro[{{ $index }}][motivo_inactivo]" 
                           class="form-control form-control-sm border-danger input-motivo" 
                           placeholder="¿Por qué se marca como inactivo?"
                           value="{{ isset($discoDuro->motivo_inactivo) ? trim(explode('|', $discoDuro->motivo_inactivo)[0]) : '' }}"
                           {{ isset($discoDuro) && !$discoDuro->is_active ? 'required' : '' }}>

                    @if(isset($discoDuro->motivo_inactivo) && strpos($discoDuro->motivo_inactivo, '|') !== false)
                        <div class="mt-1">
                            <span class="badge badge-secondary p-2">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ trim(explode('|', $discoDuro->motivo_inactivo)[1]) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-2">
            <small class="text-muted">ID Sistema: {{ $discoDuro->id ?? 'Pendiente' }}</small>
            @if($estaInactivo)
                <span class="badge badge-danger ml-2">Dado de baja</span>
            @endif
        </div>
    </div>
</div>