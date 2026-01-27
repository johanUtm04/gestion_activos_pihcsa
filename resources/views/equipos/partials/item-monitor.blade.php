<div class="monitor-item p-3 mb-5 border rounded bg-light shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-desktop"></i> Monitores #
            <span class="numero-index badge badge-secondary">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
        </h6>
        
        <button type="button" 
                onclick="eliminarComponente(this, 'monitor-item')" 
                class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <input type="hidden" name="monitor[{{ $index }}][id]" value="{{ $monitor->id ?? '' }}">
    <input type="hidden" name="monitor[{{ $index }}][_delete]" value="">

    <div class="row">
        <div class="form-group col-md-3">
            <label class="small font-weight-bold">Marca</label>
            <select name="monitor[{{$index}}][marca]" class="form-control form-control-sm">
                <option value="">Seleccione...</option>
                @foreach([
                    'Dell',
                    'HP',
                    'LG',
                    'Samsung',
                    'Acer',
                    'ASUS',
                    'BenQ',
                    'Lenovo',
                    'Manhattan',
                    'MSI',
                    'Gigabyte',
                    'ViewSonic',
                    'Philips',
                    'AOC',
                    'Eizo',
                    'Sony',
                    'Panasonic',
                    'Xiaomi',
                    'Huawei',
                    'Otro'
                ] as $mar)
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
                @foreach([15, 17, 18.5, 19, 20, 21, 22, 23, 24, 25, 27, 28, 29, 31.5, 32, 34, 38, 40] as $pulgada)
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
               @foreach([
                'HDMI',
                'HDMI 1.4',
                'HDMI 2.0',
                'HDMI 2.1',
                'VGA',
                'DisplayPort (DP)',
                'Mini DisplayPort',
                'DVI',
                'DVI-D',
                'DVI-I',
                'USB-C (Display)',
                'Thunderbolt'
            ] as $inter)

                    <option value="{{ $inter }}" {{ ($monitor->interface ?? '') == $inter ? 'selected' : '' }}>
                        {{ $inter }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <small class="text-muted">ID Sistema: {{ $monitor->id ?? 'Pendiente' }}</small>
</div>