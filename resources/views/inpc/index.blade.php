@extends('adminlte::page')

@section('title', 'Histórico INPC')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1><i class="fas fa-chart-line mr-2 text-success"></i>Índices Nacionales de Precios (INPC)</h1>
        <button class="btn btn-success" data-toggle="modal" data-target="#modalInpc"><i class="fas fa-plus mr-2"></i>Registrar Índice</button>
    </div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-sm text-center">
            <thead class="bg-light">
                <tr>
                    <th>Año</th>
                    <th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th><th>May</th><th>Jun</th>
                    <th>Jul</th><th>Ago</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inpc_agrupado as $anio => $meses)
                <tr>
                    <td class="font-weight-bold bg-light">{{ $anio }}</td>
                    @for($m = 1; $m <= 12; $m++)
                        <td class="{{ isset($meses[$m]) ? '' : 'text-light' }}">
                            {{ $meses[$m] ?? '---' }}
                        </td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalInpc" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('inpc.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Registrar Valor INPC</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label>Año</label>
                            <input type="number" name="anio" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                        <div class="col-6">
                            <label>Mes</label>
                            <select name="mes" class="form-control">
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->monthName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label>Valor del Índice (INPC)</label>
                        <input type="number" name="valor" class="form-control" step="0.0001" placeholder="Ej: 113.0180" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Guardar Índice</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop