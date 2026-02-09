<fieldset class="border p-3 mb-4">
    <legend class="w-auto px-2 text-primary"><i class="fas fa-info-circle"></i>Información del sistema</legend>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Rol</label>
                <input class="form-control border-0 bg-white" value="{{ auth()->user()->rol }}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Departamento</label>
                <input class="form-control border-0 bg-white" value="{{ auth()->user()->departamento }}" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Estatus</label>
                <input class="form-control border-0 bg-white" value="{{ auth()->user()->estatus }}" disabled>
            </div>
        </div>
    </div>
</fieldset>