<div class="periferico-item p-3 mb-5 border rounded bg-light shadow-sm item-componente">
        <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-secondary mb-0">
            <i class="fas fa-desktop"></i> Periferico #
            <span class="numero-index badge badge-secondary">
                {{ is_numeric($index) ? $index + 1 : 'Nuevo' }} 
            </span>
        </h6>
        
        <button type="button" 
                onclick="eliminarComponente(this, 'periferico-item')" 
                class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash"></i>
        </button>
    </div>

    <input type="hidden" name="periferico[{{ $index }}][id]" value="{{ $periferico->id ?? '' }}">
    <input type="hidden" name="periferico[{{ $index }}][_delete]" value="">

    <div class="row">
    <div class="form-group col-md-3">
        <label class="small font-weight-bold">Tipo de periférico</label>
        <select name="periferico[{{ $index }}][tipo]" class="form-control form-control-sm">
            <option value="">Seleccione...</option>
            @foreach([
                'Mouse',
                'Ratón',
                'Teclado',
                'Teclado mecánico',
                'Monitor',
                'Audífonos',
                'Diadema',
                'Bocinas',
                'Webcam',
                'Micrófono',
                'Impresora',
                'Escáner',
                'Control / Gamepad',
                'Joystick',
                'Volante',
                'Tablet gráfica',
                'Lector de tarjetas',
                'Proyector',
                'Otro'
            ] as $t)

                <option value="{{ $t }}" {{ ($periferico->tipo ?? '') == $t ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label class="small font-weight-bold">Marca</label>
        <select name="periferico[{{ $index }}][marca]" class="form-control form-control-sm">
            <option value="">Seleccione...</option>
            @foreach([
                'Logitech',
                'HP',
                'Dell',
                'Lenovo',
                'Microsoft',
                'Razer',
                'Corsair',
                'SteelSeries',
                'HyperX',
                'Redragon',
                'Asus',
                'Acer',
                'Samsung',
                'LG',
                'BenQ',
                'Sony',
                'JBL',
                'Bose',
                'Epson',
                'Canon',
                'Brother',
                'Manhattan',
                'Generic / Genérica',
                'Otra'
            ] as $marca)
                <option value="{{ $marca }}" {{ ($periferico->marca ?? '') == $marca ? 'selected' : '' }}>
                    {{ $marca }}
                </option>
            @endforeach
        </select>
    </div>


    <div class="form-group col-md-3">
        <label class="small font-weight-bold">Serial</label>
        <input type="text" name="periferico[{{ $index }}][serial]" 
                value="{{ $periferico->serial ?? '' }}" class="form-control form-control-sm" placeholder="S/N">
    </div>

    <div class="form-group col-md-3">
        <label class="small font-weight-bold">Interfaz / Conexión</label>
        <select name="periferico[{{ $index }}][interface]" class="form-control form-control-sm">
            <option value="{{ $periferico->interface ?? '' }}" selected>
             {{ $periferico->interface ?? 'Seleccione...' }}
            </option>
                @foreach([
                    'USB',
                    'USB-A',
                    'USB-B',
                    'USB-C',
                    'Micro USB',
                    'Mini USB',
                    'USB 3.0',
                    'USB 3.1',
                    'USB 3.2',
                    'USB4',
                    'Thunderbolt',
                    'Thunderbolt 2',
                    'Thunderbolt 3',
                    'Thunderbolt 4',
                    'Bluetooth',
                    'Bluetooth 4.0',
                    'Bluetooth 4.2',
                    'Bluetooth 5.0',
                    'Bluetooth 5.1',
                    'Bluetooth 5.2',
                    'Bluetooth 5.3',
                    'Inalámbrico (2.4 GHz)',
                    'Inalámbrico (5 GHz)',
                    'Wi-Fi',
                    'Wi-Fi 4',
                    'Wi-Fi 5',
                    'Wi-Fi 6',
                    'Wi-Fi 6E',
                    'Wi-Fi 7',
                    'HDMI',
                    'HDMI 1.4',
                    'HDMI 2.0',
                    'HDMI 2.1',
                    'Mini HDMI',
                    'Micro HDMI',
                    'DisplayPort',
                    'DisplayPort 1.2',
                    'DisplayPort 1.4',
                    'DisplayPort 2.0',
                    'Mini DisplayPort',
                    'VGA',
                    'DVI',
                    'DVI-D',
                    'DVI-I',
                    'DVI-A',
                    'Jack 3.5 mm',
                    'Jack 6.3 mm',
                    'Audio óptico (TOSLINK)',
                    'Audio coaxial',
                    'XLR',
                    'RCA',
                    'PS/2',
                    'Ethernet',
                    'Ethernet Gigabit',
                    'Ethernet 2.5G',
                    'Ethernet 10G',
                    'RJ11',
                    'Serial (RS-232)',
                    'Paralelo (LPT)',
                    'SATA',
                    'eSATA',
                    'IDE',
                    'M.2',
                    'PCIe',
                    'FireWire',
                    'FireWire 400',
                    'FireWire 800',
                    'SD',
                    'microSD',
                    'CompactFlash',
                    'NFC',
                    'IR (Infrarrojo)',
                    'Dock propietario',
                    'Conector magnético',
                    'Otro'
                ] as $int)

                <option value="{{ $int }}" {{ ($periferico->interface ?? '') == $int ? 'selected' : '' }}>
                    {{ $int }}
                </option>
            @endforeach
        </select>
    </div>
    </div>

    <div class="row align-items-center mt-2 border-top pt-2">
        <div class="col-md-4">
            <div class="custom-control custom-switch">
                <input type="checkbox" 
                       class="custom-control-input switch-estado-componente" 
                       id="switch-perif-{{ $index }}" 
                       name="periferico[{{ $index }}][is_active]" 
                       value="1" 
                       {{ !isset($periferico) || $periferico->is_active ? 'checked' : '' }}>
                <label class="custom-control-label small font-weight-bold {{ !isset($periferico) || $periferico->is_active ? 'text-success' : 'text-danger' }}" 
                       for="switch-perif-{{ $index }}">
                    {{ !isset($periferico) || $periferico->is_active ? 'COMPONENTE ACTIVO' : 'COMPONENTE INACTIVO' }}
                </label>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="div-motivo" style="{{ !isset($periferico) || $periferico->is_active ? 'display: none;' : '' }}">
                <input type="text" 
                       name="periferico[{{ $index }}][motivo_inactivo]" 
                       class="form-control form-control-sm border-danger input-motivo" 
                       placeholder="¿Por qué se marca como inactivo? (Ej: Quemado, reemplazo...)"
                       value="{{ $periferico->motivo_inactivo ?? '' }}"
                       {{ isset($periferico) && !$periferico->is_active ? 'required' : '' }}>
            </div>
        </div>
    </div>

    <small class="text-muted">ID Sistema: {{ $periferico->id ?? 'Pendiente' }}</small>
        @if(isset($periferico) && $periferico->is_active == false)
           <span class="badge badge-danger">Dado de baja</span>
        @endif
</div>