<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-depreciacion mb-0">
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
                            <button class="btn btn-sm btn-outline-info btn-depreciar"
                                data-marca="{{ $equipo->marca->nombre ?? 'Sin Marca' }}"
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