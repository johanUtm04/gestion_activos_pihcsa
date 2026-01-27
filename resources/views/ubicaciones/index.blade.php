@extends('adminlte::page')

@section('title', 'Gestión de Ubicaciones')

@section('css')
<style>
    /* --- Estética de Tabla Premium --- */
    .table-ubicaciones {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-ubicaciones thead th {
        background-color: #fdecea; /* Fondo suave rojizo */
        color: #dc3545; 
        font-weight: 800;
        border-bottom: 3px solid #dc3545;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 15px;
        border-top: none;
    }

    .table-ubicaciones tbody td {
        vertical-align: middle; 
        padding: 15px;
        font-size: 16px;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-ubicaciones tbody tr:hover {
        background-color: rgba(220, 53, 69, 0.02) !important;
        transition: all 0.2s ease;
    }

    .secondary-data {
        color: #888; 
        font-size: 0.85em;
        display: block; 
        margin-top: 2px;
    }

    /* Estilo para los Badges de estado */
    .badge-status {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
    }
</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="text-dark font-weight-bold">
            <i class="fas fa-map-marker-alt text-danger mr-2"></i>Gestión de Ubicaciones
        </h1>
        <p class="text-muted mb-0">Administración de sedes y departamentos físicos</p>
    </div>
    <a href="{{ route('ubicaciones.create') }}" class="btn btn-danger shadow-sm px-4 font-weight-bold">
        <i class="fas fa-plus-circle mr-1"></i> Agregar Ubicación
    </a>
</div>
@stop

@section('content')

{{-- Alertas --}}
@php $alertTypes = ['success', 'danger', 'warning', 'info']; @endphp
@foreach ($alertTypes as $msg)
    @if(Session::has($msg))
        <div class="alert alert-{{ $msg }} alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-info-circle mr-2"></i> {{ Session::get($msg) }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
@endforeach

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
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $ubicacion->id }}</td>

                        {{-- UBICACIÓN --}}
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

                        {{-- CÓDIGO --}}
                        <td>
                            <span class="badge badge-light border px-2 py-1" style="font-size: 0.9rem;">
                                <i class="fas fa-hashtag text-danger mr-1 small"></i> {{ $ubicacion->codigo }}
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td class="text-center">
                            <div class="btn-group shadow-sm" role="group">
                                <a href="{{ route('ubicaciones.edit', $ubicacion) }}" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('ubicaciones.destroy', $ubicacion) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            title="Eliminar"
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