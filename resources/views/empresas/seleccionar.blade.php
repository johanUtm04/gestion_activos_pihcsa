@extends('adminlte::page')

@section('title', 'Selección de Sucursal Activa')

@section('css')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #1e5799 0%, #2989d8 50%, #207cca 100%);
        --success-soft: #e8f5e9;
        --info-soft: #e3f2fd;
        --border-radius-lg: 12px;
    }

    .selector-container {
        min-height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .info-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        background: #fff;
        width: 100%;
        max-width: 460px;
    }

    .label-header {
        color: #8392a5;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .section-title {
        display: flex;
        align-items: center;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .section-title i {
        margin-right: 12px;
        color: #28a745;
        background: var(--success-soft);
        padding: 10px;
        border-radius: 8px;
    }
</style>
@stop

@section('content')
<div class="selector-container">
    
    <div class="card info-card border-top border-success" style="border-top-width: 5px !important;">
        <div class="card-body p-4">
            
            <div class="text-center mb-4">
                <h5 class="section-title justify-content-center m-0">
                    <i class="fas fa-building"></i> Control de Activos Multisucursal
                </h5>
                <p class="text-muted small mt-2">
                    Detectamos que iniciaste sesión correctamente. Por seguridad, selecciona la entidad con la que trabajarás en esta sesión.
                </p>
            </div>

            <form action="{{ route('empresa.guardar') }}" method="POST">
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show text-sm" role="alert">
                        <i class="icon fas fa-ban mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="form-group mb-4">
                    <label for="empresa_id" class="label-header">Entidad o Sucursal Activa</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0">
                                <i class="fas fa-industry text-muted"></i>
                            </span>
                        </div>
                        <select name="empresa_id" id="empresa_id" class="form-control form-control-lg border-left-0 font-weight-bold" style="color: #2c3e50;" required>
                            <option value="" disabled selected>-- Elige una organización --</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg btn-block shadow-sm font-weight-bold">
                    <i class="fas fa-sign-in-alt mr-2"></i> Confirmar e Ingresar
                </button>
            </form>

        </div>
    </div>

</div>
@stop