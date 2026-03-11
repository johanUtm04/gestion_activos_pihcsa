@extends('adminlte::page')

@section('title', 'Modo Escaneo')

@section('content')
<div class="container-fluid pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            {{-- Card con el borde superior azul (info) como el resto de tu sistema --}}
            <div class="card card-outline card-info shadow-lg">
                <div class="card-header text-center">
                    <h3 class="card-title float-none font-weight-bold">
                        <i class="fas fa-barcode text-info mr-2"></i> Lector de Activos Pinesa
                    </h3>
                </div>
                
                <div class="card-body">
                    <p class="text-muted text-center mb-4">
                        El sistema redirigirá automáticamente al encontrar el ID o Serial.
                    </p>

                    <form action="{{ route('equipos.procesar') }}" method="POST" id="form-pistola">
                        @csrf
                        <div class="form-group">
                            {{-- Clases de Bootstrap: form-control-lg para que sea grande y fácil de ver --}}
                            <input type="text" 
                                   name="serial" 
                                   id="codigo_pistola" 
                                   class="form-control form-control-lg text-center font-weight-bold shadow-sm"
                                   placeholder="ESPERANDO ESCANEO..." 
                                   autofocus 
                                   onblur="this.focus()"
                                   style="height: 80px; font-size: 2rem; border: 2px solid #17a2b8; letter-spacing: 2px;"
                                   autocomplete="off">
                        </div>
                    </form>

                    {{-- Mensaje de ayuda visual --}}
                    <div class="text-center mt-4">
                        <div class="spinner-grow text-info spinner-grow-sm" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                        <span class="text-secondary ml-2">Escáner listo para recibir datos...</span>
                    </div>
                </div>

                <div class="card-footer text-center bg-white">
                    <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    document.getElementById('codigo_pistola').focus();

    document.getElementById('form-pistola').onsubmit = function() {
        document.getElementById('codigo_pistola').style.backgroundColor = '#e9ecef';
        document.getElementById('codigo_pistola').placeholder = 'PROCESANDO...';
    };
</script>
@stop