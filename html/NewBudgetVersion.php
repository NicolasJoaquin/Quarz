<?php
// exit(print_r($this->budget->info['ship_method_name']));
?>
<h4>Nueva versión de la cotización #<?php echo sprintf("%'.04d\n", $this->budgetNumber )?></h4>
<div class="row">
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="codDetail"><strong>#</strong></label>
        <p id="codDetail" class="rounded text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->budgetNumber) ?></p>
        <input type="hidden" name="budgetNumber" id="budgetNumber" value="<?php echo $this->budgetNumber ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label rounded text-bg-secondary fs-6" for="userDetail"><strong>Usuario</strong></label>
        <p id="userDetail" class="rounded text-bg-light fs-6"></p>
    </div>
    <div class="col-md-4">
        <label class="form-label rounded text-bg-secondary fs-6" for="clientDetail"><strong>Cliente</strong></label>
        <p id="clientDetail" class="rounded text-bg-light fs-6"></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="dateDetail"><strong>Fecha</strong></label>
        <p id="dateDetail" class="rounded text-bg-light fs-6"></p>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="shipMethod"><strong>Medio de envío</strong></label>
        <select class="form-select" name="shipMethod" id="shipMethod"></select>

    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="payMethod"><strong>Medio de pago</strong></label>
        <select class="form-select" name="payMethod" id="payMethod"></select>
    </div>
    <div class="col-md-6">
        <label class="form-label rounded text-bg-secondary fs-6" for="desc"><strong>Notas</strong></label>
        <textarea id="desc" class="form-control rounded fs-6 text-desc"></textarea>
    </div>
</div>
<hr>
<div class="title mb-2">
    <div class="d-flex bd-highlight">
        <h4 class="me-auto">Ítems</h4>
        <button type="button" class="btn btn-success btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#itemToAddModal"><i class="bi bi-plus-lg"></i> Agregar ítem</button>
        <!-- Modal (Nuevo ítem) -->
        <div class="modal fade modal-lg" id="itemToAddModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel">Nuevo ítem</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 item-to-add">
                            <div class="col-sm-12 mb-3">
                                <label for="itemToAdd" class="form-label">Producto a agregar</label>
                                <select class="form-select" style="width: 100%" name="itemToAdd" id="itemToAdd">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="itemToAddCost" class="form-label">Costo</label>
                                <input type="number" class="form-control" id="itemToAddCost" name="itemToAddCost" placeholder="">
                            </div>
                            <div class="col-md-4">
                                <label for="itemToAddPrice" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="itemToAddPrice" name="itemToAddPrice" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label for="itemToAddQuantity" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="itemToAddQuantity" name="itemToAddQuantity" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label for="itemToAddCurrentQuantity" class="form-label">En stock</label>
                                <input type="number" class="form-control" id="itemToAddCurrentQuantity" name="itemToAddCurrentQuantity" placeholder="" disabled readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="itemToAddId" class="form-label">Código</label>
                                <input type="number" class="form-control" id="itemToAddId" name="itemToAddId" placeholder="" disabled readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="itemToAddDesc" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="itemToAddDesc" name="itemToAddDesc" placeholder="" disabled readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="itemToAddTotalPrice" class="form-label">Total</label>
                                <input type="number" class="form-control" id="itemToAddTotalPrice" name="itemToAddTotalPrice" placeholder="" disabled readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" id="addItem"><i class="bi bi-plus-lg"></i> Agregar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal (Modificar ítem) -->
        <div class="modal fade modal-lg" id="itemToEditModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar ítem</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 item-to-edit">
                            <div class="col-sm-12 mb-3">
                                <!-- <label for="itemToEdit" class="form-label">Producto a agregar</label> -->
                                <!-- <select class="form-select" style="width: 100%" name="itemToEdit" id="itemToEdit">
                                </select> -->
                            </div>
                            <input type="hidden" name="itemToEditIndex" id="itemToEditIndex">
                            <div class="col-md-4">
                                <label for="itemToEditCost" class="form-label">Costo</label>
                                <input type="number" class="form-control" id="itemToEditCost" name="itemToEditCost" placeholder="">
                            </div>
                            <div class="col-md-4">
                                <label for="itemToEditPrice" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="itemToEditPrice" name="itemToEditPrice" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label for="itemToEditQuantity" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="itemToEditQuantity" name="itemToEditQuantity" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label for="itemToEditCurrentQuantity" class="form-label">En stock</label>
                                <input type="number" class="form-control" id="itemToEditCurrentQuantity" name="itemToEditCurrentQuantity" placeholder="" disabled readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="itemToEditId" class="form-label">Código</label>
                                <input type="number" class="form-control" id="itemToEditId" name="itemToEditId" placeholder="" disabled readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="itemToEditDesc" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="itemToEditDesc" name="itemToEditDesc" placeholder="" disabled readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="itemToEditTotalPrice" class="form-label">Total</label>
                                <input type="number" class="form-control" id="itemToEditTotalPrice" name="itemToEditTotalPrice" placeholder="" disabled readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="editItem"><i class="bi bi-pencil-square"></i> Editar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="table-wrapper">
        <table class="table table-striped table-hover mb-none">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">Pos.</div></th>
                    <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">#</div></th>
                    <th scope="col" class="col-md-3"><div class="d-flex bd-highlight">Nombre</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">$</div></th>
                    <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">Cant.</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Total</div></th>
                    <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Acciones</div></th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="table-wrapper scrolleable mb-none">
        <table class="table table-striped table-hover mb-none">
            <tbody id="itemsTableBody">
            </tbody>
        </table>
    </div>
    <div class="table-wrapper">
        <table class="table table-striped table-hover mb-none">

            <tfoot class="table-dark">
                <tr>
                    <td colspan="6">Subotal</td>
                    <td colspan="1">
                        <span class="d-flex bd-highlight" id="subtotalDetail"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">Descuento</td>
                    <td colspan="1">
                        <span class="d-flex bd-highlight">
                            <div class="d-flex bd-highlight input-group">
                                <select name="discountType" id="discountType" class="col-md-2 form-select input-group-text">
                                    <option value="1" selected>$</option>
                                    <!-- <option value="2">%</option> -->
                                </select>
                                <input class="form-control input-compact" type="number" name="discount" id="discount">
                            </div>
                        </span>
                    </td>

                    <td colspan="1">Recargo</td>
                    <td colspan="1">
                        <span class="d-flex bd-highlight">
                            <div class="d-flex bd-highlight input-group">
                                <select name="taxType" id="taxType" class="form-select input-group-text">
                                    <option value="1" selected>$</option>
                                    <!-- <option value="2">%</option> -->
                                </select>
                                <input class="form-control input-compact" type="number" name="tax" id="tax">
                            </div>
                        </span>
                    </td>
                    <td colspan="1">Envío</td>
                    <td colspan="1">
                        <span class="d-flex bd-highlight">
                            <div class="d-flex bd-highlight input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control input-compact" type="number" name="ship" id="ship">
                            </div>
                        </span>
                    </td>
                    <td colspan="1"></td>
                </tr>
                <tr>
                    <td colspan="6">Total</td>
                    <td colspan="1">
                        <span class="d-flex bd-highlight" id="totalDetail"></span>
                    </td>
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
        <a class="btn btn-primary mb-3" type="button" id="goBack" href="./viewBudget-<?php echo $this->budgetNumber ?>">
            <i class="bi bi-arrow-bar-left"></i>
            Volver
        </a>
    </div>
    <div class="col-2">
        <button class="btn btn-success mb-3" type="button" id="newVersion">
            <i class="bi bi-check-lg"></i>
            Guardar v<?php echo $this->budgetVersion+1 ?>
        </button>
    </div>
</div>

