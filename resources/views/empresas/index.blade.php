@extends('adminlte::page')

@section('title', 'Catálogo de Empresas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center px-1">
        <div>
            <h1 class="font-weight-bold text-dark mb-1">Catálogo de Empresas</h1>
            <p class="text-muted text-sm mb-0">Control operativo e institucional de las organizaciones del sistema</p>
        </div>
        <a href="{{ route('empresas.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Nueva Empresa
        </a>
    </div>
@stop

@section('content')
    @if($targetId = (session('new_id') ?? session('actualizado_id')))
        <span id="scroll-target-marker" data-id="{{ $targetId }}"></span>
    @endif

    @if (session('success') || session('warning') || session('danger'))
        <div class="px-1 mb-3">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning border-0 shadow-sm alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            @if (session('danger'))
                <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <i class="fas fa-ban mr-2"></i> {{ session('danger') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body py-3">
            <form action="{{ route('empresas.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-9 form-group mb-md-0">
                        <label for="buscar_nombre" class="text-xs text-muted font-weight-bold text-uppercase">Buscar Empresa</label>
                        <input type="text" class="form-control" id="buscar_nombre" name="nombre" value="{{ request('nombre') }}" placeholder="Ej. PIHCSA o Corporación Azul" style="border-radius: 8px;">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold">
                            <i class="fas fa-search mr-1"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-tipos mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px" class="text-center">ID</th>
                            <th>Razón Social / Organización</th>
                            <th>Identificación Fiscal</th>
                            <th class="text-center" style="width: 150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($empresas as $empresa)
                        <tr id="empresa-{{ $empresa->id }}">
                            <td class="text-center font-weight-bold text-muted">{{ $empresa->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        @if(session('actualizado_id') == $empresa->id)
                                            <span class="badge badge-warning badge-status">Editado</span>
                                        @endif
                                        @if(session('new_id') == $empresa->id)
                                            <span class="badge badge-success badge-status">Nuevo</span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong class="text-dark d-block text-uppercase">{{ $empresa->nombre }}</strong>
                                        <span class="secondary-data">
                                            <i class="fas fa-building mr-1"></i>Entidad Corporativa / Sucursal
                                        </span>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                @if($empresa->rfc)
                                    <span class="text-primary font-weight-bold text-uppercase">
                                        <i class="fas fa-id-card mr-1"></i> {{ $empresa->rfc }}
                                    </span>
                                @else
                                    <span class="text-muted small italic">
                                        <i class="fas fa-ban mr-1"></i> Sin RFC asignado
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-sm btn-outline-danger" title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                                onclick="return confirm('¿Seguro que deseas eliminar la empresa: {{ $empresa->nombre }}?')">
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
                                No hay empresas configuradas en el sistema
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($empresas->hasPages())
            <div class="card-footer bg-white border-top-0 py-3" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                {{ $empresas->links() }}
            </div>
        @endif
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        var marker = $('#scroll-target-marker');
        if(marker.length) {
            var targetId = marker.data('id');
            var targetRow = $('#empresa-' + targetId);
            if(targetRow.length) {
                $('html, body').animate({ scrollTop: targetRow.offset().top - 150 }, 800);
            }
        }
    });
</script>
@stop