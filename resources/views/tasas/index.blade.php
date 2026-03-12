@extends('adminlte::page')

@section('title', 'Configuración de Tasas')

@section('content_header')
    <h1><i class="fas fa-percent mr-2 text-info"></i>Catálogo de Tasas LISR</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title font-weight-bold">Nueva Tasa</h3></div>
            <form action="{{ route('tasas.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Nombre del Concepto</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Equipo de Cómputo" required>
                    </div>
                    <div class="form-group">
                        <label>Porcentaje Anual (%)</label>
                        <input type="number" name="porcentaje" class="form-control" step="0.01" placeholder="30.00" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-block">Guardar Tasa</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Tasa %</th>
                            <th>Descripción</th>
                            <th style="width: 100px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasas as $tasa)
                        <tr>
                            <td class="font-weight-bold">{{ $tasa->nombre }}</td>
                            <td><span class="badge badge-info">{{ $tasa->porcentaje }}%</span></td>
                            <td class="small text-muted">{{ $tasa->descripcion }}</td>
                            <td>
                                <button class="btn btn-xs btn-default text-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop