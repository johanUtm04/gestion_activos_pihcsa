<fieldset class="fieldset-group mb-4">
    <legend><i class="fas fa-id-card text-info mr-2"></i>Información personal</legend>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" class="form-control"
            name="name" value="{{ old('name', auth()->user()->name) }}" required>
    </div>

    <div class="form-group">
        <label>Correo electrónico</label>
        <input type="email" class="form-control"
            name="email" value="{{ old('email', auth()->user()->email) }}" required>
    </div>
</fieldset>