@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-dismissible fade show" role="alert" style="background-color: #fff3cd; color: #FD7E14; border: 1px solid #ffeeba;">
        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" style="color: #FD7E14;">&times;</button>
    </div>
@endif

@if(session('danger'))
    <div class="alert alert-dismissible fade show" role="alert" style="background-color: #fdecea; color: #FD7E14; border: 1px solid #fbc7c3;">
        <i class="fas fa-times-circle"></i> {{ session('danger') }}
        <button type="button" class="close" data-dismiss="alert" style="color: #FD7E14;">&times;</button>
    </div>
@endif
