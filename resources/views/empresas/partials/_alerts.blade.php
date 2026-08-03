@php
    $alertTypes = [
        'success' => [
            'icon' => 'fa-check-circle',
            'title' => '¡Éxito!'
        ],
        'danger' => [
            'icon' => 'fa-exclamation-circle',
            'title' => 'Error'
        ],
        'warning' => [
            'icon' => 'fa-exclamation-triangle',
            'title' => 'Aviso'
        ],
        'info' => [
            'icon' => 'fa-info-circle',
            'title' => 'Información'
        ],
    ];
@endphp

@foreach ($alertTypes as $type => $config)
    @if(session()->has($type))
        <div class="alert alert-{{ $type }}
                    alert-dismissible
                    fade show
                    shadow-sm
                    border-0
                    animated
                    fadeInDown"
             role="alert"
             style="border-radius: 8px;
                    border-left: 5px solid rgba(0, 0, 0, 0.20) !important;">

            <div class="d-flex align-items-center">
                <i class="fas {{ $config['icon'] }} mr-2 fa-lg"></i>

                <div>
                    <strong class="text-uppercase">
                        {{ $config['title'] }}
                    </strong>

                    <br>

                    {{ session($type) }}
                </div>
            </div>

            <button type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Cerrar"
                    style="color: inherit;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach