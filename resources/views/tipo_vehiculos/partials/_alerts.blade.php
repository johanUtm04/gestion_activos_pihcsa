{{-- Definimos los tipos de alerta que maneja el controlador --}}
@php 
    $alertTypes = [
        'success' => 'fa-check-circle', 
        'danger'  => 'fa-exclamation-circle', 
        'warning' => 'fa-exclamation-triangle', 
        'info'    => 'fa-info-circle', 
        'primary' => 'fa-user'
    ]; 
@endphp

@foreach ($alertTypes as $type => $icon)
    @if(Session::has($type))
        <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm border-0 animated fadeInDown" 
             role="alert" 
             style="border-radius: 8px; border-left: 5px solid rgba(0,0,0,0.2) !important;">
            
            <div class="d-flex align-items-center">
                <i class="fas {{ $icon }} mr-2 fa-lg"></i>
                <div>
                    <strong class="text-uppercase">{{ $type === 'success' ? '¡Éxito!' : 'Aviso' }}</strong><br>
                    {{ Session::get($type) }}
                </div>
            </div>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: inherit;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach