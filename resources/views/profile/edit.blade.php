@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/perfil/edit.css') }}">
@stop

@section('content_header')
    @include('profile.partials.edit._header')
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Aplicamos la animación de caída que te gustó --}}
            <div class="card card-outline card-info animated-box" style="animation-delay: 0.1s;">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">
                        @include('profile.partials.edit._form_personal')
                        @include('profile.partials.edit._form_security')
                        @include('profile.partials.edit._form_system')
                    </div>

                    @include('profile.partials.edit._footer')
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(document).ready(function () {
        $('#successModal').modal('show');
    });
</script>
@stop