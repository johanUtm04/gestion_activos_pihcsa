@extends('adminlte::page')

@section('title', 'Asignar Factura')

@section('content_header')
    <h1>Asignar Factura al Equipo</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Activo</h3>
                </div>
                <div class="card-body">
                    <p><strong>Equipo:</strong> {{ $equipo->tipoActivo->nombre ?? 'N/A' }} - {{ $equipo->marca->nombre ?? 'S/M' }}</p>
                    <p><strong>Serial:</strong> <span class="badge badge-info">{{ $equipo->serial }}</span></p>
                    <hr>
                    
                    <form action="{{ route('equipos.update_factura', $equipo) }}" method="POST">
                        @csrf
                        @method('POST') {{-- O PUT si así lo definiste en web.php --}}
                        
                        <div class="form-group">
                            <label for="numero_factura">Número de Factura / Documento</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-file-invoice-dollar"></i></span>
                                </div>
                                <input type="text" 
                                       name="numero_factura" 
                                       id="numero_factura" 
                                       class="form-control @error('numero_factura') is-invalid @enderror" 
                                       placeholder="Ej: FAC-2026-001"
                                       value="{{ old('numero_factura', $equipo->numero_factura) }}"
                                       required>
                                @error('numero_factura')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="text-muted">Este número se utilizará para trazabilidad contable.</small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Guardar Factura
                            </button>
                            <a href="{{ route('equipos.index') }}" class="btn btn-default">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Panel lateral informativo: Evita que la pantalla se vea vacía --}}
        <div class="col-md-4">
            <div class="info-box bg-light">
                <span class="info-box-icon"><i class="fas fa-question-circle text-muted"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">¿Por qué asignar una factura?</span>
                    <span class="info-box-number" style="font-weight: normal; font-size: 0.9rem;">
                        Permite vincular el activo físico con el registro de compra legal, facilitando auditorías y reclamos de garantía.
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@stop