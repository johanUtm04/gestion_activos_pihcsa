@extends('adminlte::page')

@section('title', 'Gestión de Sucursales')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="font-weight-bold text-dark mb-0">
            <i class="fas fa-code-branch text-info mr-2"></i>
            Gestión de Sucursales
        </h4>
        <small class="text-muted">
            Genera bases de datos operativas por sucursal.
        </small>
    </div>
</div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if(session('danger'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <i class="fas fa-ban mr-2"></i>
        {{ session('danger') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="row">
    <div class="col-lg-5">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i>
                    Generar nueva sucursal
                </h3>
            </div>

            <form method="POST" action="{{ route('sucursales.generar') }}">
                @csrf

                <div class="card-body">

                    <div class="form-group">
                        <label>Nombre visible</label>
                        <input type="text"
                               name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}"
                               placeholder="Ej. Querétaro"
                               required>
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Clave técnica</label>
                        <input type="text"
                               name="clave"
                               class="form-control @error('clave') is-invalid @enderror"
                               value="{{ old('clave') }}"
                               placeholder="Ej. queretaro"
                               required>
                        <small class="text-muted">
                            Sin espacios. Ejemplo: queretaro, guadalajara, monterrey.
                        </small>
                        @error('clave')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nombre de base de datos</label>
                        <input type="text"
                               name="database_name"
                               class="form-control @error('database_name') is-invalid @enderror"
                               value="{{ old('database_name') }}"
                               placeholder="Ej. pihcsa_queretaro"
                               required>
                        <small class="text-muted">
                            Debe iniciar con <strong>pihcsa_</strong>. Solo letras, números y guion bajo.
                        </small>
                        @error('database_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Opcional">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="alert alert-warning py-2 mb-0">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Esta acción creará una nueva base de datos y copiará la estructura base del sistema.
                    </div>

                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-database mr-1"></i>
                        Generar Sucursal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-list mr-1"></i>
                    Sucursales registradas
                </h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Clave</th>
                            <th>Base de datos</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sucursales as $sucursal)
                            <tr>
                                <td class="font-weight-bold">{{ $sucursal->nombre }}</td>
                                <td>
                                    <span class="badge badge-light border">
                                        {{ $sucursal->clave }}
                                    </span>
                                </td>
                                <td>
                                    <code>{{ $sucursal->database_name }}</code>
                                </td>
                                <td>
                                    @if($sucursal->estatus === 'activo')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No hay sucursales registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop