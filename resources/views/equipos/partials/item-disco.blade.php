<div class="discoDuro-item p-3 mb-5 border rounded bg-light shadow-sm item-componente">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-hdd"></i> Disco Duro #
            <span class="numero-index badge badge-secondary">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
        </h6>
        
        <button type="button" 
                onclick="eliminarComponente(this, 'discoDuro-item')" 
                class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <input type="hidden" name="discoDuro[{{ $index }}][id]" value="{{ $discoDuro->id ?? '' }}">
    
    <input type="hidden" name="discoDuro[{{ $index }}][_delete]" value="">

    <div class="row">
        <div class="form-group col-md-4">
            <label class="small font-weight-bold">Capacidad</label>
            <select name="discoDuro[{{$index}}][capacidad]" class="form-control form-control-sm">
                <option value="">Seleccione...</option>
                @foreach([
                '120GB',
                '128GB',
                '240GB',
                '256GB',
                '480GB',
                '500GB',
                '512GB',
                '1TB',
                '2TB',
                '3TB',
                '4TB',
                '6TB',
                '8TB',
                '10TB',
                '12TB',
                '16TB'
            ] as $cap)
                    <option value="{{ $cap }}" {{ ($discoDuro->capacidad ?? '') == $cap ? 'selected' : '' }}>
                        {{ $cap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-4">
            <label class="small font-weight-bold">Tipo</label>
            <select name="discoDuro[{{$index}}][tipo_hdd_ssd]" class="form-control">
                <option value="">Seleccione...</option>
                @foreach([
                    'HDD',
                    'SSD',
                    'SATA SSD',
                    'M.2 SATA',
                    'M.2 NVMe',
                    'PCIe NVMe',
                    'Hybrid SSHD',
                    'External HDD',
                    'External SSD',
                    'Otro'
                ] as $tipo)

                    <option value="{{ $tipo }}" {{ ($discoDuro->tipo_hdd_ssd ?? '') == $tipo ? 'selected' : '' }}>
                        {{ $tipo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-4">
            <label class="small font-weight-bold">Interface</label>
<select name="discoDuro[{{$index}}][interface]" class="form-control form-control-sm">
    <option value="">Seleccione...</option>
        @foreach([
            'SATA',
            'SATA III',
            'NVMe',
            'PCIe NVMe',
            'M.2 NVMe',
            'USB',
            'USB 3.0',
            'USB-C',
            'Thunderbolt',
            'SAS',
            'eSATA',
            'Otro'
        ] as $tipo)

        <option value="{{ $tipo }}" 
            {{ trim(strtoupper($discoDuro->interface ?? '')) === strtoupper($tipo) ? 'selected' : '' }}>
            {{ $tipo }}
        </option>
    @endforeach
</select>
        </div>
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
                <div class="div-motivo" style="{{ !isset($discoDuro) || $discoDuro->is_active ? 'display: none;' : '' }}">
                    <input type="text" 
                        name="discoDuro[{{ $index }}][motivo_inactivo]" 
                        class="form-control form-control-sm border-danger input-motivo" 
                        placeholder="¿Por qué se marca como inactivo? (Ej: Quemado, reemplazo...)"
                        value="{{ $discoDuro->motivo_inactivo ?? '' }}"
                        {{ isset($discoDuro) && !$discoDuro->is_active ? 'required' : '' }}>
                </div>
            </div>
        </div>

        <small class="text-muted">ID Sistema: {{ $discoDuro->id ?? 'Pendiente' }}</small>
        @if(isset($discoDuro) && $discoDuro->is_active == false)
            <span class="badge badge-danger">Dado de baja</span>
        @endif
    </div>
</div>