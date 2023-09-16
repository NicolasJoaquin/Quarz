<h4>Ventas del cliente <strong>#<?php echo sprintf("%'.04d\n", $this->client['client_id']) ?></strong></h4>
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
<button class="btn btn-primary mb-1" type="button" id="goBack">
    <i class="bi bi-arrow-bar-left"></i>
    Volver
</button>
<hr>
<div class="row g-5 mb-5">
    <div class="col-md-12 col-lg-12 order-md-last">
        <div class="table-wrapper table-responsive">
            <table class="table table-striped table-hover mb-none">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                N° 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Usuario 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Pres. 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Fecha 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Envío 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Pago 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Total 
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Acciones
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="scrolleable" id="tableBody">
<?php
foreach($this->sales as $s) {
?>
                    <tr id="saleRow_<?php echo $s['sale_id'] ?>">
                        <th scope="row"><?php echo $s['sale_id'] ?></th>
                        <td><?php echo $s['user_name'] ?></td>
                        <td><?php echo $s['budget_number'] ? "#".sprintf("%'.04d\n", $s['budget_number'])." > v".sprintf("%'.02d\n", $s['budget_version']) : "-" ?></td>
                        <td><?php echo $s['start_date'] ?></td>
                        <td><?php echo $s['ship_name'] ?></td>
                        <td><?php echo $s['pay_name'] ?></td>
                        <td>$<?php echo $s['total'] ?></td>
                        <td><i aria-href="viewSale-<?php echo $s['sale_id'] ?>" id="viewSale-<?php echo $s['sale_id'] ?>" class="bi bi-search ms-auto text-primary view-sale"></i></td>
                    </tr>
<?php
}
?>
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="1">
                            Cant. total de ventas
                        </td>
                        <td colspan="1">
                            <span class="d-flex bd-highlight" id="registers"><?php echo $this->registers ?></span>
                        </td>
                        <td colspan="3">
                        </td>
                        <td colspan="1">
                            Total gastado
                        </td>
                        <td colspan="1">
                            <span class="d-flex bd-highlight text-left" id="total">$<?php echo $this->total ?></span>
                        </td>
                        <td colspan="1">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>