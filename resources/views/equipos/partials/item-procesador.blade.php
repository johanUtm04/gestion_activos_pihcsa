<div class="procesador-item p-3 mb-5 border rounded bg-light shadow-sm item-componente"> {{-- Añadí 'item-componente' para el JS --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-microchip"></i> Procesadores # 
            <span class="numero-index badge badge-secondary">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
        </h6>
        
        <button type="button" 
                onclick="eliminarComponente(this, 'procesador-item')" 
                class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <input type="hidden" name="procesador[{{ $index }}][id]" value="{{ $procesador->id ?? '' }}">
    <input type="hidden" name="procesador[{{ $index }}][_delete]" value="">

    <div class="row">
        <div class="form-group col-md-6">
            <label class="small font-weight-bold">Marca</label>
            <select name="procesador[{{$index}}][marca]" class="form-control form-control-sm">
                <option value="">Seleccione...</option>
                @foreach(['Intel','AMD','Apple','Qualcomm','MediaTek','IBM','NVIDIA','VIA','Dell','HP','Lenovo','ASUS','Acer','Samsung','LG','Microsoft','Huawei','MSI','Gigabyte','Otro'] as $mar)
                    <option value="{{ $mar }}" {{ ($procesador->marca ?? '') == $mar ? 'selected' : '' }}>
                        {{ $mar }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            <label class="small font-weight-bold">Descripcion/Tipo</label>
            <input type="text" name="procesador[{{$index}}][descripcion_tipo]" class="form-control form-control-sm"
            value="{{ $procesador->descripcion_tipo ?? '' }}" placeholder="Modelo O Nombre">
        </div>
    </div>

    <div class="row align-items-center mt-2 border-top pt-2">
        <div class="col-md-4">
            <div class="custom-control custom-switch">
                <input type="checkbox" 
                       class="custom-control-input switch-estado-componente" 
                       id="switch-proc-{{ $index }}" 
                       name="procesador[{{ $index }}][is_active]" 
                       value="1" 
                       {{ !isset($procesador) || $procesador->is_active ? 'checked' : '' }}>
                <label class="custom-control-label small font-weight-bold {{ !isset($procesador) || $procesador->is_active ? 'text-success' : 'text-danger' }}" 
                       for="switch-proc-{{ $index }}">
                    {{ !isset($procesador) || $procesador->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                </label>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="div-motivo" style="{{ !isset($procesador) || $procesador->is_active ? 'display: none;' : '' }}">
                <input type="text" 
                       name="procesador[{{ $index }}][motivo_inactivo]" 
                       class="form-control form-control-sm border-danger input-motivo" 
                       placeholder="¿Por qué se marca como inactivo? (Ej: Quemado, reemplazo...)"
                       value="{{ $procesador->motivo_inactivo ?? '' }}"
                       {{ isset($procesador) && !$procesador->is_active ? 'required' : '' }}>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-2">
        <small class="text-muted">ID Sistema: {{ $procesador->id ?? 'Pendiente' }}</small>
        @if(isset($procesador) && $procesador->is_active == false)
            <span class="badge badge-danger">Dado de baja</span>
        @endif
    </div>
</div>