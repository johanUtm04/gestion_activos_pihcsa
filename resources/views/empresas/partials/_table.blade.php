@if($targetId = (session('new_id') ?? session('actualizado_id')))
    <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
@endif

<div class="card shadow-sm border-0 empresa-table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-empresas mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center">
                            ID
                        </th>

                        <th>
                            Razón Social / Organización
                        </th>

                        <th>
                            Identificación Fiscal
                        </th>

                        <th style="width: 150px;" class="text-center">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($empresas as $empresa)
                        <tr id="empresa-{{ $empresa->id }}">
                            <td class="text-center font-weight-bold text-muted">
                                {{ $empresa->id }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        @if(session('actualizado_id') == $empresa->id)
                                            <span class="badge badge-warning empresa-status-badge">
                                                Editado
                                            </span>
                                        @endif

                                        @if(session('new_id') == $empresa->id)
                                            <span class="badge badge-success empresa-status-badge">
                                                Nuevo
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <strong class="text-dark d-block text-uppercase">
                                            {{ $empresa->nombre }}
                                        </strong>

                                        <span class="empresa-secondary-data">
                                            <i class="fas fa-building mr-1 text-muted"></i>
                                            Entidad Corporativa / Area
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($empresa->rfc)
                                    <span class="empresa-text-pink font-weight-bold text-uppercase">
                                        <i class="fas fa-id-card mr-1"></i>
                                        {{ $empresa->rfc }}
                                    </span>
                                @else
                                    <span class="text-muted small font-italic">
                                        <i class="fas fa-ban mr-1"></i>
                                        Sin RFC asignado
                                    </span>
                                @endif
                            </td>

                        <td class="text-center">
                            <div class="btn-group shadow-sm" role="group">

                                <a href="{{ route('empresas.edit', $empresa->id) }}"
                                class="btn btn-sm btn-pink-pure"
                                title="Editar área">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('empresas.destroy', $empresa->id) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Eliminar área"
                                            onclick="return confirm('¿Seguro que deseas eliminar el área: {{ $empresa->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 d-block empresa-empty-icon"></i>

                                No hay empresas configuradas en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($empresas->hasPages())
        <div class="card-footer bg-white border-top-0 py-3 empresa-card-footer">
            {{ $empresas->links() }}
        </div>
    @endif
</div>