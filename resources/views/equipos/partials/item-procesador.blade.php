<div class="procesador-item p-3 mb-5 border rounded bg-light shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-desktop"></i> Procesadores #
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
                @foreach([
                    'Intel',
                    'AMD',
                    'Apple',
                    'Qualcomm',
                    'MediaTek',
                    'IBM',
                    'NVIDIA',
                    'VIA',
                    'Dell',
                    'HP',
                    'Lenovo',
                    'ASUS',
                    'Acer',
                    'Samsung',
                    'LG',
                    'Microsoft',
                    'Huawei',
                    'MSI',
                    'Gigabyte',
                    'Otro'
                ] as $mar)
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
    <small class="text-muted">ID Sistema: {{ $procesador->id ?? 'Pendiente' }}</small>
</div>