@if($targetId = (session('new_id') ?? session('actualizado_id')))
    <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
@endif

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-departamentos mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center">ID</th>
                        <th>Nombre del Departamento</th>
                        <th>Cant. Equipos</th>
                        <th class="text-center" style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($departamentos as $departamento)
                    <tr id="departamento-{{ $departamento->id }}">
                        <td class="text-center font-weight-bold text-muted">{{ $departamento->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    @if(session('actualizado_id') == $departamento->id)
                                        <span class="badge badge-warning badge-status">Editado</span>
                                    @endif
                                    @if(session('new_id') == $departamento->id)
                                        <span class="badge badge-success badge-status">Nuevo</span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-dark d-block text-uppercase">{{ $departamento->nombre }}</strong>
                                    <span class="secondary-data">
                                        <i class="fas fa-building mr-1 text-orange-pure"></i>Área Organizacional
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light border px-2 py-1">
                                <i class="fas fa-desktop text-muted mr-1"></i>
                                {{ $departamento->equiposCount() }} registrados
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('departamentos.edit', $departamento) }}" class="btn btn-sm btn-outline-warning" style="color: #FD7E14; border-color: #FD7E14;" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('departamentos.destroy', $departamento) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary"
                                            onclick="return confirm('¿Seguro que deseas eliminar el departamento: {{ $departamento->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-building fa-3x mb-3 d-block text-orange-pure" style="opacity: 0.3;"></i>
                            No hay departamentos registrados
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($departamentos->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $departamentos->links() }}
        </div>
    @endif
</div>
