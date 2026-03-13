@php
    $estaInactivo = isset($procesador) && !$procesador->is_active;
    $valOriginalFrec = $procesador->clock_ghz ?? '';
@endphp

<div class="procesador-item p-3 mb-3 border rounded {{ $estaInactivo ? 'bg-light border-danger opacity-75' : 'bg-light shadow-sm' }} item-componente">
    
    {{-- ENCABEZADO COMPACTO --}}
    <div class="d-flex justify-content-between align-items-center {{ $estaInactivo ? '' : 'mb-3' }}">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-microchip"></i> Procesador # 
            <span class="numero-index badge {{ $estaInactivo ? 'badge-danger' : 'badge-secondary' }}">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
            @if($estaInactivo)
                <span class="badge badge-danger ml-2"><i class="fas fa-exclamation-circle"></i> INACTIVO</span>
            @endif
        </h6>

        <div class="btn-group">
            <button type="button" 
                    class="btn btn-sm btn-outline-info mr-2" 
                    data-toggle="collapse" 
                    data-target="#collapseProc-{{ $index }}" 
                    aria-expanded="{{ $estaInactivo ? 'false' : 'true' }}">
                <i class="fas fa-eye"></i> {{ $estaInactivo ? 'Ver detalles' : 'Contraer' }}
            </button>
        </div>
    </div>

    {{-- CUERPO COLAPSABLE --}}
    <div id="collapseProc-{{ $index }}" class="collapse {{ $estaInactivo ? '' : 'show' }} mt-3">
        
        <input type="hidden" name="procesador[{{ $index }}][id]" value="{{ $procesador->id ?? '' }}">
        <input type="hidden" name="procesador[{{ $index }}][_delete]" value="">

        <div class="row">
            {{-- Marca --}}
            <div class="form-group col-md-4">
                <label class="small font-weight-bold">Marca</label>
                <select name="procesador[{{$index}}][marca]" 
                        class="form-control form-control-sm"
                        data-current="{{ $procesador->marca ?? '' }}">
                    <option value="">Seleccione...</option>
                    @foreach(['Intel','AMD','Apple','Qualcomm','MediaTek','IBM','NVIDIA','VIA','Dell','HP','Lenovo','ASUS','Acer','Samsung','LG','Microsoft','Huawei','MSI','Gigabyte','Otro'] as $mar)
                        <option value="{{ $mar }}" {{ ($procesador->marca ?? '') == $mar ? 'selected' : '' }}>
                            {{ $mar }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Descripción/Tipo --}}
            <div class="form-group col-md-4">
                <label class="small font-weight-bold">Descripcion/Tipo</label>
                <input type="text" name="procesador[{{$index}}][descripcion_tipo]" 
                       class="form-control form-control-sm"
                       value="{{ $procesador->descripcion_tipo ?? '' }}" 
                       data-current="{{ $procesador->descripcion_tipo ?? '' }}"
                       placeholder="Modelo O Nombre">
            </div>

            {{-- Frecuencia --}}
            <div class="form-group col-md-4">
                <label class="small font-weight-bold">Frecuencia (GHz)</label>
                
                <div class="input-group input-group-sm">
                    @php
                        $rangoFrec = [];
                        for ($i = 0.9; $i <= 5.0; $i += 0.1) { $rangoFrec[] = number_format($i, 2); }
                        $especialesFrec = ['1.30', '1.70', '2.42', '2.59', '3.19', '3.33'];
                        $frecuenciasFinales = collect($rangoFrec)->merge($especialesFrec)->unique()->sort()->values();
                        
                        $valorActual = number_format((float)($procesador->clock_ghz ?? 0), 2);
                        $esOtro = !empty($procesador->clock_ghz) && !$frecuenciasFinales->contains($valorActual);
                        
                        // Determinar el valor del select para data-current
                        $selectCurrentValue = $esOtro ? 'otro' : $valorActual;
                    @endphp

                    <select id="select_frec_{{$index}}" 
                            class="form-control form-control-sm" 
                            data-current="{{ $selectCurrentValue }}"
                            onchange="checkOtroFrec(this, {{$index}})">
                        <option value="">Seleccione...</option>
                        @foreach($frecuenciasFinales as $frec)
                            <option value="{{ $frec }}" {{ $valorActual == $frec ? 'selected' : '' }}>
                                {{ $frec }} GHz
                            </option>
                        @endforeach
                        <option value="otro" {{ $esOtro ? 'selected' : '' }}>[ Otro valor ]</option>
                    </select>

                    <input type="number" 
                           step="0.01" 
                           name="procesador[{{$index}}][clock_ghz]" 
                           id="input_frec_{{$index}}"
                           class="form-control form-control-sm {{ $esOtro ? '' : 'd-none' }}" 
                           placeholder="0.00"
                           value="{{ $procesador->clock_ghz ?? '' }}"
                           data-current="{{ $valOriginalFrec }}">
                </div>
            </div>
        </div>

        {{-- Switch de Estado y Motivo --}}
        <div class="row align-items-center mt-2 border-top pt-2">
            <div class="col-md-4">
                <div class="custom-control custom-switch">
                    <input type="checkbox" 
                           class="custom-control-input switch-estado-componente" 
                           id="switch-proc-{{ $index }}" 
                           name="procesador[{{ $index }}][is_active]" 
                           value="1" 
                           data-current="{{ !isset($procesador) || $procesador->is_active ? '1' : '0' }}"
                           {{ !isset($procesador) || $procesador->is_active ? 'checked' : '' }}>
                    <label class="custom-control-label small font-weight-bold {{ !isset($procesador) || $procesador->is_active ? 'text-success' : 'text-danger' }}" 
                           for="switch-proc-{{ $index }}">
                        {{ !isset($procesador) || $procesador->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                    </label>
                </div>
            </div>
            
            <div class="col-md-8">
                @php 
                    $motivoActual = isset($procesador->motivo_inactivo) ? trim(explode('|', $procesador->motivo_inactivo)[0]) : '';
                @endphp
                <div class="div-motivo" style="{{ !isset($procesador) || $procesador->is_active ? 'display: none;' : '' }}">
                    <input type="text" 
                           name="procesador[{{ $index }}][motivo_inactivo]" 
                           class="form-control form-control-sm border-danger input-motivo" 
                           placeholder="¿Por qué se marca como inactivo?"
                           value="{{ $motivoActual }}"
                           data-current="{{ $motivoActual }}"
                           {{ isset($procesador) && !$procesador->is_active ? 'required' : '' }}>

                    @if(isset($procesador->motivo_inactivo) && strpos($procesador->motivo_inactivo, '|') !== false)
                        <div class="mt-1">
                            <span class="badge badge-secondary p-2">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ trim(explode('|', $procesador->motivo_inactivo)[1]) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-2">
            <small class="text-muted">ID Sistema: {{ $procesador->id ?? 'Pendiente' }}</small>
        </div>
    </div>
</div>