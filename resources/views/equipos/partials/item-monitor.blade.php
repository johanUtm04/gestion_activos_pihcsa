@php
    $estaInactivo = isset($monitor) && !$monitor->is_active;
@endphp

<div class="monitor-item p-3 mb-3 border rounded {{ $estaInactivo ? 'bg-light border-danger opacity-75' : 'bg-light shadow-sm' }} item-componente">
    
    {{-- ENCABEZADO COMPACTO --}}
    <div class="d-flex justify-content-between align-items-center {{ $estaInactivo ? '' : 'mb-3' }}">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-desktop"></i> Monitores #
            <span class="numero-index badge {{ $estaInactivo ? 'badge-danger' : 'badge-secondary' }}">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
            @if($estaInactivo)
                <span class="badge badge-danger ml-2"><i class="fas fa-eye-slash"></i> INACTIVO (Fuera de servicio)</span>
            @endif
        </h6>

        <div class="btn-group">
            <button type="button" 
                    class="btn btn-sm btn-outline-info mr-2" 
                    data-toggle="collapse" 
                    data-target="#collapseMon-{{ $index }}" 
                    aria-expanded="{{ $estaInactivo ? 'false' : 'true' }}">
                <i class="fas fa-eye"></i> {{ $estaInactivo ? 'Ver detalles' : 'Contraer' }}
            </button>
        </div>
    </div>

    {{-- CUERPO COLAPSABLE --}}
    <div id="collapseMon-{{ $index }}" class="collapse {{ $estaInactivo ? '' : 'show' }} mt-3">
        
        <input type="hidden" name="monitor[{{ $index }}][id]" value="{{ $monitor->id ?? '' }}">
        <input type="hidden" name="monitor[{{ $index }}][_delete]" value="">

        <div class="row">
            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Marca</label>
                <select name="monitor[{{$index}}][marca]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach(['Dell','HP','LG','Samsung','Acer','ASUS','BenQ','Lenovo','Manhattan','MSI','Gigabyte','ViewSonic','Philips','AOC','Eizo','Sony','Panasonic','Xiaomi','Huawei','Otro'] as $mar)
                        <option value="{{ $mar }}" {{ ($monitor->marca ?? '') == $mar ? 'selected' : '' }}>
                            {{ $mar }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Serial</label>
                <input type="text" name="monitor[{{$index}}][serial]" class="form-control form-control-sm"
                       value="{{ $monitor->serial ?? '' }}" placeholder="Ej. SN-123456789">
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Escala En Pulgadas</label>
                <select name="monitor[{{$index}}][escala_pulgadas]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach([14,15, 17, 18.5, 19, 20, 21, 22, 23, 24, 25, 27, 28, 29, 31.5, 32, 34, 38, 40] as $pulgada)
                        <option value="{{ $pulgada }}" {{ ($monitor->escala_pulgadas ?? '') == $pulgada ? 'selected' : '' }}>
                            {{ $pulgada }}"
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label class="small font-weight-bold">Interface</label>
                <select name="monitor[{{$index}}][interface]" class="form-control form-control-sm">
                    <option value="">Seleccione...</option>
                    @foreach(['Integrado', 'HDMI','HDMI 1.4','HDMI 2.0','HDMI 2.1','VGA','DisplayPort (DP)','Mini DisplayPort','DVI','DVI-D','DVI-I','USB-C (Display)','Thunderbolt'] as $inter)
                        <option value="{{ $inter }}" {{ ($monitor->interface ?? '') == $inter ? 'selected' : '' }}>
                            {{ $inter }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Switch de Estado y Motivo --}}
        <div class="row align-items-center mt-2 border-top pt-2">
            <div class="col-md-4">
                <div class="custom-control custom-switch">
                    <input type="checkbox" 
                           class="custom-control-input switch-estado-componente" 
                           id="switch-mon-{{ $index }}" 
                           name="monitor[{{ $index }}][is_active]" 
                           value="1" 
                           {{ !isset($monitor) || $monitor->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label small font-weight-bold {{ !isset($monitor) || $monitor->is_active ? 'text-success' : 'text-danger' }}" 
                           for="switch-mon-{{ $index }}">
                        {{ !isset($monitor) || $monitor->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                    </label>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="div-motivo" @if(!isset($monitor) || $monitor->is_active) style="display: none;" @endif>
                    <input type="text" 
                           name="monitor[{{ $index }}][motivo_inactivo]" 
                           class="form-control form-control-sm border-danger input-motivo" 
                           placeholder="¿Por qué se marca como inactivo?"
                           value="{{ isset($monitor->motivo_inactivo) ? trim(explode('|', $monitor->motivo_inactivo)[0]) : '' }}"
                           {{ isset($monitor) && !$monitor->is_active ? 'required' : '' }}>

                        @if(isset($monitor->motivo_inactivo) && strpos($monitor->motivo_inactivo, '|') !== false)
                        <div class="mt-1">
                            <span class="badge badge-secondary p-2">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ trim(explode('|', $monitor->motivo_inactivo)[1]) }}
                            </span>
                        </div>
                        @endif
                </div>
            </div>
        </div>

        <div class="mt-2">
            <small class="text-muted">ID Sistema: {{ $monitor->id ?? 'Pendiente' }}</small>
            @if($estaInactivo)
                <span class="badge badge-danger">Dado de baja</span>
            @endif
        </div>
    </div>
</div>