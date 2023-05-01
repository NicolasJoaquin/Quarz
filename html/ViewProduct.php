
<!-- <div class="row">
    <div class="text-right col-md-6">
        <img class="d-block mx-auto mb-none" src="./extras/quarz-logo.png" alt="" width="72" height="90">
        <h2 class="text-center">Visualización de productos</h2>
    </div>
    <div class="text-right col-md-6 mt-5">
        <p class="lead">
            Podés filtrar y ordenar los productos por su nombre. <br>
            Hacé click en la fila de cualquier producto para ver su detalle o editarlo. <br>
        </p>
    </div>
</div> -->
<h4>Detalle del producto</h4>
<div class="row">
    <div class="col-md-2">
        <label class="form-label badge text-bg-secondary fs-6" for="codDetail">Código</label><br>
        <p id="codDetail" class="badge text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->product['product_id']) ?></p>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="descDetail"><strong>Descripción</strong></label>
        <p id="descDetail"><?php echo $this->product['product_name'] ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label" for="packDetail"><strong>Unidad de empaque</strong></label>
        <p id="packDetail"><?php echo $this->product['packing_unit'] ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label" for="stockDetail"><strong>En stock</strong></label>
        <p id="stockDetail"><?php echo $this->product['product_quantity'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label" for="provDetail"><strong>Proveedor</strong></label>
        <p id="provDetail"><?php echo $this->product['provider_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="costDetail"><strong>Costo</strong></label>
        <p id="costDetail"><?php echo $this->product['cost_price'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="priceDetail"><strong>Precio de venta</strong></label>
        <p id="priceDetail"><?php echo $this->product['product_price'] ?></p>
    </div>
</div>
<hr>
<h4>Editar producto</h4>
<div class="row">
    <div class="col-md-4">
        <label class="form-label" for="editCost"><strong>Costo</strong></label>
        <input type="number" class="form-control" id="editCost" name="editCost" placeholder="Edite el costo del producto" value="<?php echo $this->product['cost_price'] ?>">        
    </div>
    <div class="col-md-4">
        <label class="form-label" for="editPrice"><strong>Precio de venta</strong></label>
        <input type="number" class="form-control" id="editPrice" name="editPrice" placeholder="Edite el precio de venta del producto" value="<?php echo $this->product['product_price'] ?>">        
    </div>
    <div class="col-md-4">
        <label class="form-label" for="editStock"><strong>Stock</strong></label>
        <input type="number" class="form-control" id="editStock" name="editStock" placeholder="Edite el stock del producto" value="<?php echo $this->product['product_quantity'] ?>">        
    </div>
</div>
<div class="row mt-2">
    <div class="d-grid gap-2 col-2 text-right"> 
        <button class="btn btn-success" type="button" id="viewMoves">
            <i class="bi bi-check-lg"></i>
            Guardar
        </button>
    </div>
</div>
<hr>
<h4>Acciones</h4>
<div class="row">
    <div class="col-2"> 
        <button class="btn btn-success" type="button" id="viewMoves">
            <i class="bi bi-list-ul"></i>
            Ver movimientos
        </button>
    </div>
    <div class="col-2">
        <button class="btn btn-primary" type="button" id="goBack">
            <i class="bi bi-list-check"></i>
            Volver
        </button>
    </div>
</div>


