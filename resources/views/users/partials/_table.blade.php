@if($targetId = (session('new_id') ?? session('actualizado_id') ?? session('actualizado_factura') ?? session('new_mantenimiento')))
    <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
@endif

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-users mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px" class="text-center">ID</th>
                        <th>Usuario / Email</th>
                        <th>Rol</th>
                        <th>Departamento</th>
                        <th>Estatus</th>
                        <th class="text-center" style="width: 140px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr id="user-{{ $user->id }}">
                        <td class="text-center font-weight-bold text-muted">{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    {{-- Cambiamos 'actualizado->id' por 'actualizado_id' --}}
                                    @if(session('actualizado_id') == $user->id)
                                        <span class="badge badge-warning badge-status-pill animate__animated animate__flash">Editado</span>
                                    @endif
                                    
                                    @if(session('new_id') == $user->id)
                                        <span class="badge badge-success badge-status-pill animate__animated animate__bounceIn">Nuevo</span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-dark d-block">{{ $user->name }}</strong>
                                    <span class="secondary-data">
                                        <i class="fas fa-envelope mr-1 small"></i>{{ $user->email }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-muted"><i class="fas fa-user-shield mr-1 small"></i>{{ $user->rol }}</span></td>
                        <td><span class="text-muted"><i class="fas fa-building mr-1 small"></i>{{ $user->departamento }}</span></td>
                        <td>
                            @include('users.partials._status_badge', ['status' => $user->estatus])
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-success" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('¿Eliminar al usuario {{ $user->name }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>