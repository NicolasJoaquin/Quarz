<h4>Detalle de la venta #<?php echo sprintf("%'.04d\n", $this->sale->info['sale_id']) ?></h4>
<div class="row">
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="codDetail"><strong>#</strong></label>
        <p id="codDetail" class="rounded text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->sale->info['sale_id']) ?></p>
        <input type="hidden" name="saleId" id="saleId" value="<?php echo $this->sale->info['sale_id'] ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="userDetail"><strong>Usuario</strong></label>
        <p id="userDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['user_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="clientDetail"><strong>Cliente</strong></label>
        <p id="clientDetail" class="rounded text-bg-light fs-6"><?php echo $this->sale->info['client_name'] ?></p>
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
        <p id="payDetail" class="rounded text-bg-light fs-6 d-flex bd-highlight">
            <?php echo $this->sale->info['pay_name'] ?>
            <button type="button" class="btn btn-success btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#payStatesChanges"><i class="bi bi-clock-history"></i> Ver cambios</button>    
        </p>
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
                    <td colspan="10">Subotal</td>
                    <td colspan="2">
                        <span class="d-flex bd-highlight" ><?php echo "$" . $this->sale->info["subtotal"] ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Descuento</td>
                    <td colspan="2">
                        <span class="d-flex bd-highlight">
                            <?php echo !empty($this->sale->info["discount"]) ? "-$" . $this->sale->info["discount"] : "<em>Sin descuento</em>" ?>
                        </span>
                    </td>
                    <td colspan="2">Recargo</td>
                    <td colspan="2">
                        <span class="d-flex bd-highlight">
                            <?php echo !empty($this->sale->info["tax"]) ? "$" . $this->sale->info["tax"] : "<em>Sin recargos</em>" ?>
                        </span>
                    </td>
                    <td colspan="2">Envío</td>
                    <td colspan="2">
                        <span class="d-flex bd-highlight">
                            <?php echo !empty($this->sale->info["ship"]) ? "$" . $this->sale->info["ship"] : "<em>Sin costo de envío</em>" ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="10">Total</td>
                    <td colspan="2">
                        <span class="d-flex bd-highlight" ><?php echo "$" . $this->sale->info["total"] ?></span>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>
</div>
<!-- Modal (Historial de cambios en los estados de pago) -->
<div class="modal fade modal-lg" id="payStatesChanges" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial de cambios > Estados de pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section class="timeline">
                    <!-- <h3>Viernes, 5 Junio 2017</h3> -->
                    <ul>
                        <li class="first-child">
                            <span class="timeline-date">9:30</span>
                            <span class="titulo">Registro y acrehhditaciones</span>
                        </li>
                        <!-- <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li> -->
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>
                        <li>
                            <span class="timeline-date">10:00</span>
                            <span class="titulo">Presentación</span>
                        </li>

                        <!-- ===== -->
                        <!-- <li>
                            <span class="hora">10:30</span>
                            <div class="charla">
                                <a href="detallePonente.html" title=”Ficha de Nombre del Ponente”><img src="./extras/quarz-logo.png" alt="Nombre del ponente"></a>
                                <a href="detallePonente.html" class="link" title=”Ficha de Nombre del Ponente”>Valentín Morales</a>Cómo dar una charla de UX sin que te dé la risa
                            </div>
                        </li> -->
                        <!-- ===== -->
                        <!-- <li class="cafe">
                            <span class="hora">12:00</span>
                            <span class="titulo">Café</span>
                        </li> -->
                    </ul>
                </section>

                <!-- <section class="timeline">
                    <ul>

                </section> -->

                <!-- <p>
                    ¿Estás seguro de pasar la cotización #<?php echo sprintf("%'.04d\n", $this->budget->info['budget_number']) ?> > v<?php echo sprintf("%'.02d\n", $this->budget->info['version']) ?> a venta?
                </p> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-success" id="newBudgetToSaleConfirm"><i class="bi bi-bag-check"></i> Confirmar</button> -->
            </div>
        </div>
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


