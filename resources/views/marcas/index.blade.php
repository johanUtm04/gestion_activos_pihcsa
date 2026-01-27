@extends('adminlte::page')

@section('title', 'Gesti�n de Marcas')

@section('css')
<style>
    .table-marcas {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-marcas thead th {
        background-color: #fdecea; 
        color: #dc3545; 
        font-weight: 800;
        border-bottom: 3px solid #dc3545;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 15px;
        border-top: none;
    }

    .table-marcas tbody td {
        vertical-align: middle; 
        padding: 15px;
        font-size: 16px;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
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
            <i class="fas fa-tags text-danger mr-2"></i>Gestion de Marcas
        </h1>
        <p class="text-muted mb-0">Catalogo maestro de fabricantes de equipos</p>
    </div>
    <a href="{{ route('marcas.create') }}" class="btn btn-danger shadow-sm px-4 font-weight-bold">
        <i class="fas fa-plus-circle mr-1"></i> Agregar Marca
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

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-marcas mb-0">
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
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $marca->id }}</td>
                        <td>
                            <strong class="text-dark d-block">{{ $marca->nombre }}</strong>
                            <span class="secondary-data">
                                <i class="fas fa-industry mr-1"></i>Fabricante Autorizado
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.9rem;">
                                {{ $marca->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm" role="group">
                                <a href="{{ route('marcas.edit', $marca) }}" class="btn btn-sm btn-outline-danger" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('marcas.destroy', $marca) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Eliminar" onclick="return confirm('�Eliminar la marca {{ $marca->nombre }}?')">
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