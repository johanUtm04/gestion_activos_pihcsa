<fieldset class="fieldset-group mb-4 bg-light p-3 rounded">
    <legend><i class="fas fa-info-circle text-secondary mr-2"></i>Información del sistema</legend>

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