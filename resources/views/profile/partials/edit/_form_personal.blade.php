<fieldset class="border p-3 mb-4">
    <legend class="w-auto px-2 text-primary"><i class="fas fa-id-card"></i>Especificaciones Generales</legend>

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