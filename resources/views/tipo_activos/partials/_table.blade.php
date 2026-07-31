@if($targetId = (session('new_id') ?? session('actualizado_id') ?? session('actualizado_factura') ?? session('new_mantenimiento')))
    <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
@endif

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-tipos mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center">ID</th>
                        <th>Descripción del Activo</th>
                        <th>Mantenimiento</th> <th>Cant. Equipos</th>
                        <th class="text-center" style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tipo_activo as $tipo)
                    <tr id="tipo-{{ $tipo->id }}">
                        <td class="text-center font-weight-bold text-muted">{{ $tipo->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    @if(session('actualizado_id') == $tipo->id)
                                        <span class="badge badge-warning badge-status">Editado</span>
                                    @endif
                                    @if(session('new_id') == $tipo->id)
                                        <span class="badge badge-success badge-status">Nuevo</span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-dark d-block text-uppercase">{{ $tipo->nombre }}</strong>
                                    <span class="secondary-data">
                                        <i class="fas fa-microchip mr-1"></i>Hardware / Recurso TI
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @if($tipo->frecuencia_meses > 0)
                                <span class="text-primary font-weight-bold">
                                    <i class="fas fa-calendar-alt mr-1"></i> Cada {{ $tipo->frecuencia_meses }} meses
                                </span>
                            @else
                                <span class="text-muted small italic">
                                    <i class="fas fa-ban mr-1"></i> No programado
                                </span>
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-light border px-2 py-1">
                                <i class="fas fa-desktop text-muted mr-1"></i> 
                                {{ $tipo->equipos->count() }} registrados
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('tipo_activos.edit', $tipo) }}" class="btn btn-sm btn-outline-info" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('tipo_activos.destroy', $tipo) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                            onclick="return confirm('¿Seguro que deseas eliminar el tipo: {{ $tipo->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted"> <i class="fas fa-box-open fa-3x mb-3 d-block opacity-2"></i>
                            No hay tipos de activo configurados
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tipo_activo->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $tipo_activo->links() }}
        </div>
    @endif
</div>