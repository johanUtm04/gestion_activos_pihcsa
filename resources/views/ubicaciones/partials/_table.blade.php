<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-ubicaciones mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center">ID</th>
                        <th>Nombre / Sede</th>
                        <th>Código</th>
                        <th class="text-center" style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($ubicaciones as $ubicacion)
                    <tr id="ubicacion-{{ $ubicacion->id }}">
                        <td class="text-center font-weight-bold text-muted">{{ $ubicacion->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    @if(session('actualizado->id') == $ubicacion->id)
                                        <span class="badge badge-warning badge-status">Editado</span>
                                    @endif
                                    @if(session('new_id') == $ubicacion->id)
                                        <span class="badge badge-success badge-status">Nuevo</span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-dark d-block">{{ $ubicacion->nombre }}</strong>
                                    <span class="secondary-data">
                                        <i class="fas fa-door-open mr-1"></i>Ubicación física
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light border px-2 py-1" style="font-size: 0.9rem;">
                                <i class="fas fa-hashtag text-danger mr-1 small"></i> {{ $ubicacion->codigo }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('ubicaciones.edit', $ubicacion) }}" class="btn btn-sm btn-outline-danger" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                            onclick="return confirm('¿Eliminar la ubicación {{ $ubicacion->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-map-marked-alt fa-3x mb-3 d-block opacity-2"></i>
                            No hay ubicaciones registradas
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ubicaciones->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $ubicaciones->links() }}
        </div>
    @endif
</div>