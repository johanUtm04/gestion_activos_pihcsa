@if($status === 'ACTIVO')
    <span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Activo</span>
@elseif($status === 'INACTIVO')
    <span class="text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i>Inactivo</span>
@else
    <span class="text-warning font-weight-bold"><i class="fas fa-pause-circle mr-1"></i>Suspendido</span>
@endif