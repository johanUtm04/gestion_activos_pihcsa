<div class="ram-item p-3 mb-5 border rounded bg-light shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-desktop"></i> Rams #
            <span class="numero-index badge badge-secondary">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
        </h6>
        
        <button type="button" 
                onclick="eliminarComponente(this, 'ram-item')" 
                class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <input type="hidden" name="ram[{{ $index }}][id]" value="{{ $ram->id ?? '' }}">
    <input type="hidden" name="ram[{{ $index }}][_delete]" value="">

    <div class="row">
        <div class="form-group col-md-4">
            <label>Capacidad (GB)</label>
            <select name="ram[{{ $index }}][capacidad_gb]" class="form-control form-control-sm">
                <option value="">Seleccione...</option>
                @foreach([1, 2, 3, 4, 6, 8, 12, 16, 24, 32, 48, 64, 96, 128] as $cap)
                    <option value="{{ $cap }}" {{ ($ram->capacidad_gb ?? '') == $cap ? 'selected' : '' }}>{{ $cap }} GB</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-4">
            <label>Clock (MHz)</label>
            <select name="ram[{{ $index }}][clock_mhz]" class="form-control form-control-sm">
                <option value="">Seleccione...</option>
                @foreach([1600, 2133, 2400, 2666, 3000, 3200, 3600, 4800, 5200, 5600, 6000] as $freq)
                    <option value="{{ $freq }}" {{ ($ram->clock_mhz ?? '') == $freq ? 'selected' : '' }}>{{ $freq }} MHz</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-4">
            <label>Tipo (DDR)</label>
            <select name="ram[{{ $index }}][tipo_chz]" class="form-control form-control-sm">
                <option value="">Seleccione...</option>
                @foreach([
                'DDR2',
                'DDR3',
                'DDR3L',
                'DDR4',
                'DDR4L',
                'DDR5',
                'LPDDR3',
                'LPDDR4',
                'LPDDR4X',
                'LPDDR5',
                'LPDDR5X'
            ] as $tipo)

                    <option value="{{ $tipo }}" {{ ($ram->tipo_chz ?? '') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <small class="text-muted">ID Sistema: {{ $ram->id ?? 'Pendiente' }}</small>
</div>