@extends('adminlte::page')

@section('title', 'Tipos de Activo | Gesti�n TI')

@section('css')
<style>
    /* Est�tica Premium de Tablas */
    .table-tipos {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-tipos thead th {
        background-color: #fdecea; /* Fondo suave rojizo */
        color: #dc3545; 
        font-weight: 800;
        border-bottom: 3px solid #dc3545;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 15px;
        border-top: none;
    }

    .table-tipos tbody td {
        vertical-align: middle; 
        padding: 15px;
        font-size: 16px;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-tipos tbody tr:hover {
        background-color: rgba(220, 53, 69, 0.02) !important;
        transition: all 0.2s ease;
    }

    .secondary-data {
        color: #888; 
        font-size: 0.85em;
        display: block; 
        margin-top: 2px;
    }
</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="text-dark font-weight-bold">
            <i class="fas fa-laptop-house text-danger mr-2"></i>Tipos de Activo
        </h1>
        <p class="text-muted mb-0">Categorizaci�n t�cnica de equipos e infraestructura</p>
    </div>
    <a href="{{ route('tipo_activos.create') }}" class="btn btn-danger shadow-sm px-4 font-weight-bold">
        <i class="fas fa-plus-circle mr-1"></i> Agregar Categor�a
    </a>
</div>
@stop

@section('content')

{{-- Alertas --}}
@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="fas fa-check-circle mr-2"></i> {{ Session::get('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if(Session::has('danger'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ Session::get('danger') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-tipos mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px" class="text-center">ID</th>
                        <th>Descripci�n del Activo</th>
                        <th>Cant. Equipos</th>
                        <th class="text-center" style="width: 150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tipo_activo as $tipo)
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $tipo->id }}</td>

                        {{-- TIPO DE ACTIVO --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <strong class="text-dark d-block">{{ strtoupper($tipo->nombre) }}</strong>
                                    <span class="secondary-data">
                                        <i class="fas fa-microchip mr-1"></i>Hardware / Recurso TI
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- CONTEO DE EQUIPOS (Relaci�n Eloquent) --}}
                        <td>
                            <span class="badge badge-light border px-2 py-1">
                                <i class="fas fa-desktop text-muted mr-1"></i> 
                                {{ $tipo->equipos->count() }} registrados
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td class="text-center">
                            <div class="btn-group shadow-sm" role="group">
                                <a href="{{ route('tipo_activos.edit', $tipo) }}" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <form action="{{ route('tipo_activos.destroy', $tipo) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-secondary" 
                                            title="Eliminar"
                                            onclick="return confirm('�Seguro que deseas eliminar el tipo: {{ $tipo->nombre }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block opacity-2"></i>
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
@endsection

@section('footer')
<footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-inline">
        <i class="fas fa-code"></i> PIHCSA � Gesti�n de Activos
    </div>
    <strong>
        <i class="fas fa-boxes"></i> Inventario de Activos TI
    </strong>
    &copy; {{ date('Y') }}
</footer>
@endsection