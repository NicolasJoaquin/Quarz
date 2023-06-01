<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Alta de producto</h4>
    <div class="row g-3">
        <div class="col-sm-9">
            <label for="desc" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="desc" placeholder="Glitter metálico rojo 5 gr." value="" required>
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-sm-3">
            <label for="packingUnit" class="form-label">Unidad de empaque</label>
            <input type="text" class="form-control" id="packingUnit" placeholder="5 gr." value="" required>
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-md-9">
            <label for="provider" class="form-label">Proveedor</label>
            <select class="form-select" id="provider" required>
                <option value="0">Elegir...</option>
            </select>
            <div class="invalid-feedback">
            </div>
        </div>
        <div class="col-3">
            <label for="costPrice" class="form-label">Costo</label>
            <div class="input-group has-validation">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="costPrice" placeholder="3650.00" required>
                <div class="invalid-feedback">
                </div>
            </div>
        </div>
        <div class="col-3">
            <label for="salePrice" class="form-label">Precio de venta</label>
            <div class="input-group has-validation">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" id="salePrice" placeholder="5600.00" required>
                <div class="invalid-feedback">
                </div>
            </div>
        </div>
        <div class="col-3">
            <label for="quantity" class="form-label">Stock</label>
            <div class="input-group has-validation">
                <span class="input-group-text">Q</span>
                <input type="number" class="form-control" id="quantity" placeholder="15.00" required>
                <div class="invalid-feedback">
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h4>Acciones</h4>
    <div class="row g-3">
        <!-- <div class="col-2">
            <button class="btn btn-primary" type="button" id="goBack">
                <i class="bi bi-arrow-bar-left"></i>
                Volver
            </button>
        </div> -->
        <div class="col-3">
            <button class="w-100 btn btn-success" type="button" id="save">
                <i class="bi bi-check-lg"></i>
                Guardar
            </button>
        </div>
    </div>
</div>