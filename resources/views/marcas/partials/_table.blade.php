@if($targetId = (session('new_id') ?? session('actualizado_id') ?? session('actualizado_factura') ?? session('new_mantenimiento')))
    <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
@endif

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-marcas mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center">ID</th>
                        <th>Nombre de la Marca</th>
                        <th>Fecha Registro</th>
                        <th class="text-center" style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($marcas as $marca)
                    <tr id="marca-{{ $marca->id }}">
                        <td class="text-center font-weight-bold text-muted">{{ $marca->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    @if(session('actualizado_id') == $marca->id)
                                        <span class="badge badge-warning badge-status">Editado</span>
                                    @endif
                                    @if(session('new_id') == $marca->id)
                                        <span class="badge badge-success badge-status">Nuevo</span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-dark d-block">{{ $marca->nombre }}</strong>
                                    <span class="secondary-data">
                                        <i class="fas fa-industry mr-1"></i>Fabricante Autorizado
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.9rem;">
                                <i class="far fa-calendar-alt mr-1"></i>{{ $marca->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-sm btn-outline-danger" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('marcas.destroy', $marca) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                            onclick="return confirm('¿Eliminar la marca {{ $marca->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-tag fa-3x mb-3 d-block opacity-2"></i>
                            No hay marcas registradas
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($marcas->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $marcas->links() }}
        </div>
    @endif
</div>