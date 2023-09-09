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
    <div class="col-4"> 
        <button class="btn btn-success" type="button" id="viewClientSales">
            <i class="bi bi-bag-check"></i>
            Ver ventas del cliente
        </button>
    </div>
    <div class="col-4"> 
        <button class="btn btn-success" type="button" id="viewClientBudgets">
            <i class="bi bi-bag-check"></i>
            Ver cotizaciones del cliente
        </button>
    </div>
</div>


