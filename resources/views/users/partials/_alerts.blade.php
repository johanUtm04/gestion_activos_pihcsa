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
        <div class="alert alert-dismissible fade show shadow-sm border-0 animated fadeInDown" 
             role="alert" 
             style="border-radius: 8px; border-left: 5px solid #146c43 !important; background-color: {{ $type === 'success' ? '#d1e7dd' : '#f8f9fa' }}; color: #198754;">
            
            <div class="d-flex align-items-center">
                <i class="fas {{ $icon }} mr-2 fa-lg" style="color: #198754;"></i>
                <div>
                    <strong class="text-uppercase" style="color: #146c43;">{{ $type === 'success' ? '¡Éxito!' : 'Aviso' }}</strong><br>
                    {{ Session::get($type) }}
                </div>
            </div>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #198754;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
