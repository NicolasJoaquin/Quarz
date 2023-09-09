<h4>Detalle del producto</h4>
<div class="row">
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="codDetail"><strong>Código</strong></label>
        <p id="codDetail" class="rounded text-bg-light fs-6"><?php echo sprintf("%'.04d\n", $this->product['product_id']) ?></p>
        <input type="hidden" name="prodId" id="prodId" value="<?php echo $this->product['product_id'] ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label rounded text-bg-secondary fs-6" for="descDetail"><strong>Descripción</strong></label>
        <p id="descDetail" class="rounded text-bg-light fs-6"><?php echo $this->product['product_name'] ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="packDetail"><strong>Unidad de empaque</strong></label>
        <p id="packDetail" class="rounded text-bg-light fs-6"><?php echo $this->product['packing_unit'] ?></p>
    </div>
    <div class="col-md-2">
        <label class="form-label rounded text-bg-secondary fs-6" for="stockDetail"><strong>En stock</strong></label>
        <p id="stockDetail" class="rounded text-bg-light fs-6"><?php echo $this->product['product_quantity'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <label class="form-label rounded text-bg-secondary fs-6" for="provDetail"><strong>Proveedor</strong></label>
        <p id="provDetail" class="rounded text-bg-light fs-6"><?php echo $this->product['provider_name'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="costDetail"><strong>Costo</strong></label>
        <p id="costDetail" class="rounded text-bg-light fs-6"><?php echo $this->product['cost_price'] ?></p>
    </div>
    <div class="col-md-3">
        <label class="form-label rounded text-bg-secondary fs-6" for="priceDetail"><strong>Precio de venta</strong></label>
        <p id="priceDetail" class="rounded text-bg-light fs-6"><?php echo $this->product['product_price'] ?></p>
    </div>
</div>
<hr>
<h4>Editar producto</h4>
<div class="row">
    <div class="col-md-4">
        <label class="form-label rounded text-bg-secondary fs-6" for="editCost"><strong>Costo</strong></label>
        <input type="number" class="form-control" id="editCost" name="editCost" placeholder="Edite el costo del producto" value="<?php echo $this->product['cost_price'] ?>">        
    </div>
    <div class="col-md-4">
        <label class="form-label rounded text-bg-secondary fs-6" for="editPrice"><strong>Precio de venta</strong></label>
        <input type="number" class="form-control" id="editPrice" name="editPrice" placeholder="Edite el precio de venta del producto" value="<?php echo $this->product['product_price'] ?>">        
    </div>
    <div class="col-md-4">
        <label class="form-label rounded text-bg-secondary fs-6" for="editStock"><strong>Stock</strong></label>
        <input type="number" class="form-control" id="editStock" name="editStock" placeholder="Edite el stock del producto" value="<?php echo $this->product['product_quantity'] ?>">        
    </div>
</div>
<div class="row mt-2">
    <div class="d-grid gap-2 col-2 text-right"> 
        <button class="btn btn-success" type="button" id="modifyProduct">
            <i class="bi bi-check-lg"></i>
            Guardar
        </button>
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
        <button class="btn btn-success" type="button" id="viewProductChanges">
            <i class="bi bi-list-ul"></i>
            Ver cambios de costos
        </button>
    </div>
    <div class="col-3"> 
        <button class="btn btn-success" type="button" id="viewPriceChanges">
            <i class="bi bi-list-ul"></i>
            Ver cambios de precios
        </button>
    </div>
    <div class="col-3"> 
        <button class="btn btn-success" type="button" id="viewStockChanges">
            <i class="bi bi-list-ul"></i>
            Ver movimientos de stock
        </button>
    </div>
</div>


