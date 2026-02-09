<fieldset class="border p-3 mb-4">
    <legend class="w-auto px-2 text-primary"><i class="fas fa-lock"></i>Seguridad</legend>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group">
        <label>Contraseña actual <span class="text-danger">*</span></label>
        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
            name="current_password">
        @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <hr class="my-4">

    <h5 class="mt-3 font-weight-bold text-dark">¿Necesitas cambiar tu contraseña?</h5>
    <p class="text-muted small">Solo completa los siguientes campos si deseas actualizar tu clave de acceso.</p>
    
    <div class="form-group">
        <label>Nueva contraseña</label>
        <input type="password" class="form-control" name="password" placeholder="Mínimo 8 caracteres">
    </div>

    <div class="form-group">
        <label>Confirmar Nueva contraseña</label>
        <input type="password" class="form-control" name="password_confirmation">
    </div>
</fieldset>