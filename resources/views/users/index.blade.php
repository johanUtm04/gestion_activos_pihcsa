@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('css')
<style>

    .table-users {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-users thead th {
        background-color: #e9f7ef; 
        color: #198754;
        font-weight: 800;
        border-bottom: 3px solid #198754;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 15px;
        border-top: none;
    }

    .table-users tbody td {
        vertical-align: middle; 
        padding: 15px;
        font-size: 16px;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-users tbody tr:hover {
        background-color: rgba(25, 135, 84, 0.02) !important;
        transition: all 0.2s ease;
    }

    .secondary-data {
        color: #888; 
        font-size: 0.85em;
        display: block; 
        margin-top: 2px;
    }

    .badge-status-pill {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 50px;
        font-weight: 600;
    }
</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="text-dark font-weight-bold">
            <i class="fas fa-users text-success mr-2"></i>Gestión de Usuarios
        </h1>
        <p class="text-muted mb-0">Control, estado y administración de cuentas de acceso</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-success shadow-sm px-4 font-weight-bold">
        <i class="fas fa-user-plus mr-1"></i> Agregar Usuario
    </a>
</div>
@stop

@section('content')

{{-- Alertas --}}
@php $alertTypes = ['success', 'danger', 'warning', 'info', 'primary']; @endphp
@foreach ($alertTypes as $msg)
    @if(Session::has($msg))
        <div class="alert alert-{{ $msg }} alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ Session::get($msg) }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
@endforeach

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

                        {{-- USUARIO --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    @if(session('actualizado->id') == $user->id)
                                        <span class="badge badge-warning badge-status-pill">Editado</span>
                                    @endif
                                    @if(session('new_id') == $user->id)
                                        <span class="badge badge-success badge-status-pill">Nuevo</span>
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

                        {{-- ROL --}}
                        <td>
                            <span class="text-muted"><i class="fas fa-user-shield mr-1 small"></i>{{ $user->rol }}</span>
                        </td>

                        {{-- DEPARTAMENTO --}}
                        <td>
                            <span class="text-muted"><i class="fas fa-building mr-1 small"></i>{{ $user->departamento }}</span>
                        </td>

                        {{-- ESTATUS --}}
                        <td>
                            @if($user->estatus === 'ACTIVO')
                                <span class="text-success font-weight-bold">
                                    <i class="fas fa-check-circle mr-1"></i>Activo
                                </span>
                            @elseif($user->estatus === 'INACTIVO')
                                <span class="text-danger font-weight-bold">
                                    <i class="fas fa-times-circle mr-1"></i>Inactivo
                                </span>
                            @else
                                <span class="text-warning font-weight-bold">
                                    <i class="fas fa-pause-circle mr-1"></i>Suspendido
                                </span>
                            @endif
                        </td>

                        {{-- ACCIONES --}}
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="btn btn-sm btn-outline-success" 
                                   title="Editar Usuario">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('users.destroy', $user) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar Usuario"
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
@endsection

@section('footer')
<footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-inline">
        <i class="fas fa-code"></i> PIHCSA · Gestión de Activos
    </div>
    <strong>
        <i class="fas fa-boxes"></i> Inventario de Activos TI
    </strong>
    &copy; {{ date('Y') }} | Desarrollado por <strong>Johan</strong>
</footer>
@endsection

@section('js')
<script>
$(document).ready(function() {
    const id = "{{ session('new_id') ?? session('actualizado->id') }}";
    if (id) {
        const row = document.getElementById('user-' + id);
        if (row) {
            row.scrollIntoView({behavior: 'smooth', block: 'center'});
            $(row).css('background-color', '#f8fff9');
            setTimeout(() => {
                $(row).animate({ backgroundColor: "transparent" }, 2000);
            }, 1000);
        }
    }
});
</script>
@stop