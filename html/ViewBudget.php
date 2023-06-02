<h4>Detalle de la cotización #<?php echo sprintf("%'.04d\n", $this->budget->info['budget_id']) ?></h4>
<div class="row">
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="codDetail"><strong>#</strong></label>
        <p id="codDetail" class="rounded text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->budget->info['budget_id']) ?></p>
        <input type="hidden" name="budgetId" id="budgetId" value="<?php echo $this->budget->info['budget_id'] ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="userDetail"><strong>Usuario</strong></label>
        <p id="userDetail" class="rounded text-bg-light fs-6"><?php echo $this->budget->info['user_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="clientDetail"><strong>Cliente</strong></label>
        <p id="clientDetail" class="rounded text-bg-light fs-6"><?php echo $this->budget->info['client_name'] ?></p>
    </div>

    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="budgetDetail"><strong>Presupuesto</strong></label>
        <p id="budgetDetail" class="rounded text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->sale->info['budget_id']) ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="dateDetail"><strong>Fecha</strong></label>
        <p id="dateDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['start_date'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="shipDetail"><strong>Estado de envío</strong></label>
        <p id="shipDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['ship_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="payDetail"><strong>Estado de pago</strong></label>
        <p id="payDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['pay_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="shipMethodDetail"><strong>Medio de envío</strong></label>
        <p id="shipMethodDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['ship_method_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="payMethodDetail"><strong>Medio de pago</strong></label>
        <p id="payMethodDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['pay_method_name'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label rounded text-bg-secondary fs-6" for="descDetail"><strong>Notas</strong></label>
        <textarea disabled readonly id="descDetail" class="rounded text-bg-light fs-6 desc-detail"><?php echo $this->sale->info['description'] ?></textarea>
    </div>
</div>
<hr>
<h4>Ítems</h4>
<div class="row">
    <div class="table-wrapper">
        <table class="table table-striped table-hover mb-none">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">Pos.</div></th>
                    <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">#</div></th>
                    <th scope="col" class="col-md-4"><div class="d-flex bd-highlight">Nombre</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">$</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Cant.</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Total</div></th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="table-wrapper scrolleable mb-none">
        <table class="table table-striped table-hover mb-none">
            <tbody id="itemsTableBody">
<?php
foreach($this->sale->items as $i) {
?>
                <tr>
                    <th class="col-md-1" scope="row"><?php echo sprintf("%'.04d\n", $i["position"]) ?></th>
                    <td class="col-md-1" scope="row"><?php echo "#" . sprintf("%'.04d\n", $i["product_id"]) ?></td>
                    <td class="col-md-4" scope="row"><?php echo $i["description"] ?></td>
                    <td class="col-md-2" scope="row"><?php echo "$" . $i["sale_price"] ?></td>
                    <td class="col-md-2" scope="row"><?php echo "x" . $i["quantity"] ?></td>
                    <td class="col-md-2" scope="row"><?php echo "$" . $i["total_price"] ?></td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </div>
    <div class="table-wrapper">
        <table class="table table-striped table-hover mb-none">
            <tfoot class="table-dark">
                <tr>
                    <th scope="col" class="col-md-10"><div class="d-flex bd-highlight">Subotal</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight"><?php echo "$" . $this->sale->info["subtotal"] ?></div></th>
                </tr>
                <tr>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Descuento</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight"><?php echo !empty($this->sale->info["discount"]) ? "-$" . $this->sale->info["discount"] : "<em>Sin descuento</em>" ?></div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Recargo</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight"><?php echo !empty($this->sale->info["tax"]) ? "$" . $this->sale->info["tax"] : "<em>Sin recargos</em>" ?></div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Envío</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight"><?php echo !empty($this->sale->info["ship"]) ? "$" . $this->sale->info["ship"] : "<em>Sin costo de envío</em>" ?></div></th>
                </tr>
                <tr>
                    <th scope="col" class="col-md-10"><div class="d-flex bd-highlight">Total</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight"><?php echo "$" . $this->sale->info["total"] ?></div></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- <div class="row mt-2">
    <div class="d-grid gap-2 col-2 text-right"> 
        <button class="btn btn-success" type="button" id="modifyProduct">
            <i class="bi bi-check-lg"></i>
            Guardar
        </button>
    </div>
</div> -->
<hr>
<h4>Acciones</h4>
<div class="row">
    <div class="col-2">
        <button class="btn btn-primary mb-3" type="button" id="goBack">
            <i class="bi bi-arrow-bar-left"></i>
            Volver
        </button>
    </div>
</div>


