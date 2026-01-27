@extends('adminlte::auth.login')

@section('auth_body')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">
        Iniciar sesión
    </button>
</form>
@endsection
