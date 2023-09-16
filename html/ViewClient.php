<h4>Detalle del cliente</h4>
<div class="row">
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="numberDetail"><strong>#</strong></label>
        <p id="numberDetail" class="rounded text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->client['client_id']) ?></p>
        <input type="hidden" name="number" id="number" value="<?php echo $this->client['client_id'] ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label rounded text-bg-secondary fs-6" for="nameDetail"><strong>Nombre/Razón social</strong></label>
        <p id="nameDetail" class="rounded text-bg-light fs-6"><?php echo $this->client['name'] ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="dniDetail"><strong>DNI</strong></label>
        <p id="dniDetail" class="rounded text-bg-light fs-6"><?php echo $this->client['dni'] ? $this->client['dni'] : " <small><em>Sin DNI </em></small>" ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="cuitDetail"><strong>CUIT</strong></label>
        <p id="cuitDetail" class="rounded text-bg-light fs-6"><?php echo $this->client['cuit'] ? $this->client['cuit'] : " <small><em>Sin CUIT </em></small>" ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <label class="form-label rounded text-bg-secondary fs-6" for="directionDetail"><strong>Dirección</strong></label>
        <p id="directionDetail" class="rounded text-bg-light fs-6"><?php echo $this->client['direction'] ? $this->client['direction'] : " <small><em>Sin dirección </em></small>" ?></p>
    </div>
    <div class="col-md-5">
        <label class="form-label rounded text-bg-secondary fs-6" for="emailDetail"><strong>E-mail</strong></label>
        <p id="emailDetail" class="rounded text-bg-light fs-6"><?php echo $this->client['email'] ? $this->client['email'] : " <small><em>Sin E-mail </em></small>" ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="phoneDetail"><strong>Teléfono</strong></label>
        <p id="phoneDetail" class="rounded text-bg-light fs-6"><?php echo $this->client['phone'] ? $this->client['phone'] : " <small><em>Sin teléfono </em></small>" ?></p>
    </div>
</div>
<hr>
<h4>Acciones</h4>
<div class="row">
    <div class="col-2">
        <button class="btn btn-primary" type="button" id="goBack">
            <i class="bi bi-arrow-bar-left"></i>
            Volver
        </button>
    </div>
    <div class="col-3"> 
        <button class="btn btn-success" type="button" id="viewClientSales">
            <i class="bi bi-bag-check"></i>
            Ver ventas del cliente
        </button>
    </div>
    <div class="col-3"> 
        <button class="btn btn-success" type="button" id="viewClientBudgets">
            <i class="bi bi-bag-heart"></i>
            Ver cotizaciones del cliente
        </button>
    </div>
    <div class="col-3"> 
        <button class="btn btn-success" type="button" id="editClientButton" data-bs-toggle="modal" data-bs-target="#editClientModal">
            <i class="bi bi-pencil-square"></i>
            Editar datos
        </button>
    </div>
</div>
<!-- Modal (Modificar cliente) -->
<div class="modal fade modal-lg" id="editClientModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 client-to-edit">
                    <div class="col-sm-9">
                        <label for="name" class="form-label">Nombre/Razón social</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Jorge Britez" value="<?php echo $this->client['name'] ?>" required>
                        <small><i class="bi bi-info-circle text-primary"></i>Campo obligatorio</small>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="cuit" class="form-label">CUIL/CUIT</label>
                        <input type="text" class="form-control" id="cuit" name="cuit" placeholder="23-41146999-9" value="<?php echo $this->client['cuit'] ? $this->client['cuit'] : "" ?>">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label for="nickname" class="form-label">Apodo/Nombre de fantasía</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Jorge" value="<?php echo $this->client['nickname'] ? $this->client['nickname'] : "" ?>">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" placeholder="41146999" value="<?php echo $this->client['dni'] ? $this->client['dni'] : "" ?>" >
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="quarz@resinaepoxi.com.ar" value="<?php echo $this->client['email'] ? $this->client['email'] : "" ?>">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="11-2233-4456" value="<?php echo $this->client['phone'] ? $this->client['phone'] : "" ?>" >
                        <div class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label for="direction" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direction" name="direction" placeholder="Honduras 5663, Palermo, Buenos Aires" value="<?php echo $this->client['direction'] ? $this->client['direction'] : "" ?>">
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="editClient"><i class="bi bi-pencil-square"></i> Editar</button>
            </div>
        </div>
    </div>
</div>
