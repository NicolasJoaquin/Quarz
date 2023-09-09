<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Nuevo cliente</h4>

    <div class="row g-3">
        <div class="col-sm-9">
            <label for="name" class="form-label">Nombre/Razón social</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Jorge Britez" value="" required>
            <small><i class="bi bi-info-circle text-primary"></i>Campo obligatorio</small>
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-sm-3">
            <label for="cuit" class="form-label">CUIL/CUIT</label>
            <input type="text" class="form-control" id="cuit" name="cuit" placeholder="23-41146999-9" value="">
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-md-9">
            <label for="nickname" class="form-label">Apodo/Nombre de fantasía</label>
            <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Jorge" value="">
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" placeholder="41146999" value="" >
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-md-9">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="quarz@resinaepoxi.com.ar" value="">
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="11-2233-4456" value="" >
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-md-9">
            <label for="direction" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direction" name="direction" placeholder="Honduras 5663, Palermo, Buenos Aires" value="">
            <div class="invalid-feedback">
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h4>Acciones</h4>
    <div class="row g-3">
        <div class="col-2">
            <button class="btn btn-primary" type="button" id="goBack">
                <i class="bi bi-arrow-bar-left"></i>
                Volver
            </button>
        </div>
        <div class="col-3">
            <button class="w-100 btn btn-success" type="button" id="save">
                <i class="bi bi-check-lg"></i>
                Guardar
            </button>
        </div>
    </div>
</div>