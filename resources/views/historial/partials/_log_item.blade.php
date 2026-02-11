@php 
    $detalles = $log->detalles_json;
    $tipoOriginal = strtolower($log->tipo_registro);
    
    // Mapeo de estilos dinámicos
    $config = [
        'creacion'          => ['bg' => 'bg-success', 'icon' => 'fa-plus-circle'],
        'actualizacion'     => ['bg' => 'bg-warning', 'icon' => 'fa-sync-alt'],
        'eliminacion'       => ['bg' => 'bg-danger',  'icon' => 'fa-trash-alt'],
        'mantenimiento'     => ['bg' => 'bg-info',    'icon' => 'fa-tools'],
        'componente-extra'  => ['bg' => 'bg-orange',  'icon' => 'fa-memory'],
        'inactivacion'      => ['bg' => 'bg-danger',  'icon' => 'fa-power-off'],
        'activacion'        => ['bg' => 'bg-success', 'icon' => 'fa-bolt'], 
    ];

    // Lógica de llave final para el icono
    $llave = collect(array_keys($config))->first(fn($k) => str_contains($tipoOriginal, $k)) ?? 'default';
    $estilo = $config[$llave] ?? ['bg' => 'bg-secondary', 'icon' => 'fa-dot-circle'];
@endphp

<div class="log-card mb-4 bg-white shadow-xs">
    <div class="log-header p-3 d-flex justify-content-between align-items-center border-bottom">
        <div class="d-flex align-items-center">
            <div class="{{ $estilo['bg'] }} text-white rounded-circle mr-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px;">
                <i class="fas {{ $estilo['icon'] }}" style="font-size: 11px;"></i>
            </div>
            <span class="font-weight-bold text-dark mr-2">{{ $log->tipo_registro }}</span>
            <small class="text-muted border-left pl-2">{{ $log->created_at->format('d M, Y • H:i') }}</small>
        </div>
        <small class="badge badge-light text-muted">{{ $log->created_at->diffForHumans() }}</small>
    </div>

    <div class="p-3">
        @if(isset($detalles['cambios']))
            <table class="table table-sm table-borderless mb-0">
                @foreach($detalles['cambios'] as $campo => $valor)
                    <tr>
                        <td class="text-muted small font-weight-bold" style="width: 25%">
                            <i class="fas fa-caret-right text-primary mr-1"></i> {{ Str::headline($campo) }}
                        </td>
                        <td>
                            @if($tipoOriginal !== 'creacion' && ($valor['antes'] ?? 'N/A') !== 'N/A')
                                <span class="badge border text-danger bg-light text-decoration-line-through mr-1 px-2">
                                    {{ $valor['antes'] }}
                                </span>
                                <i class="fas fa-long-arrow-alt-right text-muted mx-1"></i>
                            @endif
                            
                            @if(Str::contains($valor['despues'], '|'))
                                <div class="bg-light border rounded p-2 mt-1 shadow-xs">
                                    <table class="table table-sm table-borderless mb-0">
                                        @foreach(explode('|', $valor['despues']) as $info)
                                            @php $partes = explode(':', $info); @endphp
                                            <tr>
                                                <td class="p-0 text-muted extra-small"><strong>{{ trim($partes[0] ?? '') }}:</strong></td>
                                                <td class="p-0 font-weight-bold text-dark extra-small">{{ trim($partes[1] ?? '') }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @else
                                <span class="badge {{ str_contains($tipoOriginal, 'componente') ? 'badge-orange-soft' : 'badge-success-soft' }} text-dark px-2">
                                    {!! $valor['despues'] ?? 'N/A' !!}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="alert bg-light border-0 mb-0 py-2 small text-muted font-italic">
                <i class="fas fa-info-circle text-info mr-1"></i> {{ $detalles['mensaje'] ?? 'Sin descripción adicional' }}
            </div>
        @endif
    </div>
    <div class="log-footer px-3 py-2 bg-gray-50 border-top d-flex justify-content-between">
        <small class="text-muted">ID Log: #{{ $log->id }}</small>
        <small class="text-muted">Operador: <span class="text-dark font-weight-bold">{{ $log->usuario->name ?? 'Sistema' }}</span></small>
    </div>
</div>