@extends('adminlte::page')

@section('title', 'Depreciación de Activos')

@section('css')
<style>

    .table-depreciacion {
        border-radius: 12px;
        overflow: hidden;
    }

    .table-depreciacion thead th {
        background-color: #f8f9fa; 
        color: #495057;
        font-weight: 800;
        border-bottom: 3px solid #6c757d;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 15px;
        border-top: none;
    }

    .table-depreciacion tbody td {
        vertical-align: middle; 
        padding: 15px;
        font-size: 15px;
        color: #495057;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-depreciacion tbody tr:hover {
        background-color: rgba(108, 117, 125, 0.03) !important;
        transition: all 0.2s ease;
    }

    .secondary-data {
        color: #888; 
        font-size: 0.85em;
        display: block; 
        margin-top: 2px;
    }

    .valor-inicial-label {
        font-weight: 700;
        color: #2c3e50;
    }

    .vida-util-badge {
        background-color: #e9ecef;
        color: #495057;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="text-dark font-weight-bold">
            <i class="fas fa-chart-line text-secondary mr-2"></i>Depreciación de Activos
        </h1>
        <p class="text-muted mb-0">Análisis financiero y valor actual de activos TI</p>
    </div>
</div>
@stop

@section('content')

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-depreciacion mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px" class="text-center">ID</th>
                        <th><i class="fas fa-desktop mr-1"></i> Activo</th>
                        <th><i class="fas fa-user mr-1"></i> Usuario</th>
                        <th><i class="fas fa-map-marker-alt mr-1"></i> Ubicación</th>
                        <th><i class="fas fa-dollar-sign mr-1"></i> Valor Inicial</th>
                        <th><i class="fas fa-calendar-alt mr-1"></i> Adquisición</th>
                        <th><i class="fas fa-hourglass-half mr-1"></i> Vida Útil</th>
                        <th class="text-center">Calcular</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($equipos as $equipo)
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $equipo->id }}</td>

                        {{-- ACTIVO --}}
                        <td>
                            <strong class="text-dark">{{ $equipo->marca?->nombre }}</strong> <br>
                            <strong class="text-dark">{{ $equipo->tipoActivo?->nombre }}</strong>
                            <span class="secondary-data">
                                {{ $equipo->tipo_equipo }} · <small class="text-muted">SN: {{ $equipo->serial ?? 'N/A' }}</small>
                            </span>
                        </td>

                        {{-- USUARIO --}}
                        <td>
                            <div class="text-muted small">
                                <i class="fas fa-user-circle mr-1"></i>
                                {{ $equipo->usuario->name ?? 'Sin asignar' }}
                            </div>
                        </td>

                        {{-- UBICACIÓN --}}
                        <td>
                            <div class="text-muted small">
                                <i class="fas fa-building mr-1"></i>
                                {{ $equipo->ubicacion->nombre ?? 'Sin ubicación' }}
                            </div>
                        </td>

                        {{-- VALOR INICIAL --}}
                        <td class="valor-inicial-label">
                            ${{ number_format($equipo->valor_inicial, 2) }}
                        </td>

                        {{-- FECHA --}}
                        <td class="text-muted">
                            {{ \Carbon\Carbon::parse($equipo->fecha_adquisicion)->format('d/M/Y') }}
                        </td>

                        {{-- VIDA ÚTIL --}}
                        <td class="text-center">
                            <span class="vida-util-badge border">
                                {{ $equipo->vida_util_estimada }} años
                            </span>
                        </td>

                        {{-- ACCIÓN --}}
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info shadow-sm btn-depreciar"
                                data-marca="{{$equipo->marca_equipo}}"
                                data-valor="{{ $equipo->valor_inicial }}"
                                data-fecha="{{ $equipo->fecha_adquisicion }}"
                                data-vida="{{ $equipo->vida_util_estimada }}"
                                title="Calcular depreciación">
                                <i class="fas fa-calculator"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block opacity-2"></i>
                            No hay activos registrados para calcular
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($equipos->hasPages())
    <div class="card-footer bg-white border-top-0">
        {{ $equipos->links() }}
    </div>
    @endif
</div>

{{-- EL MODAL SE MANTIENE IGUAL PERO CON CLASES DE BOOTSTRAP MODERNAS --}}
<div class="modal fade" id="modalDepreciacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-chart-bar mr-2 text-info"></i>Depreciación en Tiempo Real
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Activo:</span>
                    <span class="font-weight-bold text-dark" id="d-activo"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Valor Inicial:</span>
                    <span class="font-weight-bold text-primary">$<span id="d-valor"></span></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Años Transcurridos:</span>
                    <span class="badge badge-secondary px-3" id="d-añosTrasncurridos"></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Depreciación Acumulada:</span>
                    <span class="font-weight-bold text-danger">-$<span id="d-depreciado"></span></span>
                </div>
                <hr>
                <div class="bg-light p-3 rounded text-center">
                    <p class="text-muted mb-1 small uppercase font-weight-bold">Valor Actual de Mercado</p>
                    <h3 class="text-success font-weight-bold mb-0">$<span id="d-actual"></span></h3>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cerrar Análisis</button>
            </div>
        </div>
    </div>
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
document.querySelectorAll('.btn-depreciar').forEach(btn => {
    btn.addEventListener('click', function () {
        const marca = this.dataset.marca;
        const valor = parseFloat(this.dataset.valor);
        const fecha = new Date(this.dataset.fecha);
        const vida = parseInt(this.dataset.vida);
        const hoy = new Date();

        const añosTrasncurridos = Math.floor((hoy - fecha) / (1000 * 60 * 60 * 24 * 365.25));
        const depreciacionAnual = valor / vida;
        const depreciado = Math.min(depreciacionAnual * (añosTrasncurridos < 0 ? 0 : añosTrasncurridos), valor);
        const actual = valor - depreciado;

        document.getElementById('d-activo').innerText = marca;
        document.getElementById('d-valor').innerText = valor.toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('d-añosTrasncurridos').innerText = añosTrasncurridos < 0 ? 0 : añosTrasncurridos;
        document.getElementById('d-depreciado').innerText = depreciado.toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('d-actual').innerText = actual.toLocaleString('en-US', {minimumFractionDigits: 2});

        $('#modalDepreciacion').modal('show');
    });
});
</script>
@stop