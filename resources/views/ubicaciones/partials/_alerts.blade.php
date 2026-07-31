@php 
    $alertTypes = [
        'success' => 'fa-check-circle', 
        'danger'  => 'fa-exclamation-circle', 
        'warning' => 'fa-exclamation-triangle', 
        'info'    => 'fa-info-circle'
    ]; 
@endphp

@foreach ($alertTypes as $type => $icon)
    @if(Session::has($type))
        <div class="alert alert-dismissible fade show shadow-sm border-0 animated fadeInDown" 
             role="alert" 
             style="border-radius: 8px; border-left: 5px solid {{ $type === 'success' ? '#146c43' : '#dc3545' }} !important; background-color: {{ $type === 'success' ? '#d1e7dd' : '#fdecea' }}; color: {{ $type === 'success' ? '#198754' : '#dc3545' }};">
            
            <div class="d-flex align-items-center">
                <i class="fas {{ $icon }} mr-2 fa-lg" style="color: {{ $type === 'success' ? '#198754' : '#dc3545' }};"></i>
                <div>
                    <strong class="text-uppercase" style="color: {{ $type === 'success' ? '#146c43' : '#b02a37' }};">{{ $type === 'success' ? '¡Éxito!' : 'Aviso' }}</strong><br>
                    {{ Session::get($type) }}
                </div>
            </div>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: {{ $type === 'success' ? '#198754' : '#dc3545' }};">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
