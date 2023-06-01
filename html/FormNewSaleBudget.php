<div class="row mb-3">
    <div class="text-right col-md-6">
        <img class="d-block mx-auto mb-none" src="./extras/quarz-logo.png" alt="" width="72" height="90">
        <h2 class="text-center">Formulario de nueva venta o cotización</h2>
    </div>
    <div class="text-right col-md-6 mt-5">
        <p>
            <ul class="lead">
                <li>Agregá una nueva venta o cotización</li>
                <li>Las ventas no podrán darse de alta si hay productos faltantes en el stock</li>
                <li>Si te falta stock, agregá una cotización y cuando tengas disponibilidad la pasas a venta</li>
            </ul>
        </p>
    </div>
</div>

<!-- COLUMNA DERECHA -->
<div class="row g-5">
    <div class="col-md-7 col-lg-7 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Detalle de venta</span>
            <span class="badge bg-primary rounded-pill" id="quantityOfItems">0</span>
        </h4>
        <div class="col-sm-12 mb-4">
            <label for="firstName" class="form-label">Cliente</label>
            <input type="text" class="form-control" id="client" placeholder="Ingrese un cliente" value="" required="" readonly disabled>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-1">
            <button type="button" class="btn btn-outline-secondary btn-sm">Limpiar detalle</button>
        </div>
        <div class="table-wrapper">
            <table class="table table-striped table-hover mb-none">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="col-md-1">Pos.</th>
                        <!-- <th scope="col">Cod.</th> -->
                        <th scope="col" class="col-md-2">Desc.</th>
                        <th scope="col" class="col-md-2">Costo</th>
                        <th scope="col" class="col-md-2">Precio</th>
                        <th scope="col" class="col-md-1">Cant.</th>
                        <th scope="col" class="col-md-2">Total</th>
                        <th scope="col" class="col-md-2">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="table-wrapper scrolleable mb-none">
            <table class="table table-striped table-hover">
                <tbody id="detailTableBody">
                </tbody>
            </table>
        </div>
        <div class="table-wrapper mb-2">
            <table class="table table-striped table-hover mb-none">
                <tfoot>
                    <tr class="table-secondary">
                        <th scope="row" class="col-md-10">Subtotal</th>
                        <th scope="row" class="col-md-2" id="subtotalPriceDetail" name="subtotalPriceDetail"></th>
                    </tr>
                    <tr class="table-secondary">
                        <th scope="row" class="col-md-8">Descuento</th>
                        <th scope="row" class="col-md-2" id="discountPercDetail" name="discountPercDetail"></th>
                        <th scope="row" class="col-md-2" id="discountValDetail" name="discountValDetail"></th>
                    </tr>
                    <tr class="table-secondary">
                        <th scope="row" class="col-md-8">Recargos / Impuestos</th>
                        <th scope="row" class="col-md-2" id="taxPercDetail" name="taxPercDetail"></th>
                        <th scope="row" class="col-md-2" id="taxValDetail" name="taxValDetail"></th>
                    </tr>
                    <tr class="table-secondary">
                        <th scope="row" class="col-md-10">Envío</th>
                        <th scope="row" class="col-md-2" id="shipDetail" name="shipDetail"></th>
                    </tr>
                    <tr class="table-secondary">
                        <th scope="row" class="col-md-8">Medio de envío</th>
                        <th scope="row" class="col-md-4" id="shipMethodDetail" name="shipMethodDetail"></th>
                    </tr>
                    <tr class="table-dark">
                        <th scope="row" class="col-md-8">Medio de pago</th>
                        <th scope="row" class="col-md-4" id="payMethodDetail" name="payMethodDetail"></th>
                    </tr>
                    <tr class="table-dark">
                        <th scope="row" class="col-md-10">Total</th>
                        <th scope="row" class="col-md-2" id="totalPriceDetail" name="totalPriceDetail"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row mb-5">
            <button class="w-100 btn btn-lg" id="addBudget" type="submit">Agregar cotización</button>
            <button class="w-100 btn btn-lg" id="addSale" type="submit">Agregar venta</button>
        </div>
    </div>
    <!-- COLUMNA IZQUIERDA -->
    <div class="col-md-5 col-lg-5">
        <h4 class="mb-3">Agregá productos a la venta</h4>
        <form class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-sm-12">
                    <label for="clientToPush" class="form-label">Cliente</label>
                    <select class="form-select" name="clientToPush" id="clientToPush">
                    </select>
                </div>
            </div>
            <hr class="my-4">
            <div class="row g-3 prod-to-add">
                <div class="col-sm-12 mb-3">
                    <label for="productToPush" class="form-label">Producto a agregar</label>
                    <select class="form-select" name="productToPush" id="productToPush">
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="productCostToPush" class="form-label">Costo</label>
                    <input type="number" class="form-control" id="productCostToPush" name="productCostToPush" placeholder="">
                </div>
                <div class="col-md-4">
                    <label for="productPriceToPush" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="productPriceToPush" name="productPriceToPush" placeholder="">
                </div>
                <div class="col-md-2">
                    <label for="productQuantityToPush" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="productQuantityToPush" name="productQuantityToPush" placeholder="">
                </div>
                <div class="col-md-2">
                    <label for="productCurrentQuantity" class="form-label">En stock</label>
                    <input type="number" class="form-control" id="productCurrentQuantity" name="productCurrentQuantity" placeholder="" disabled readonly>
                </div>
                <div class="col-md-2">
                    <label for="productId" class="form-label">Código</label>
                    <input type="number" class="form-control" id="productId" name="productId" placeholder="" disabled readonly>
                </div>
                <div class="col-md-6">
                    <label for="productDesc" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="productDesc" name="productDesc" placeholder="" disabled readonly>
                </div>
                <div class="col-md-4">
                    <label for="productTotalPrice" class="form-label">Total</label>
                    <input type="number" class="form-control" id="productTotalPrice" name="productTotalPrice" placeholder="" disabled readonly>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-outline-success" type="button" id="addItem">Agregar al detalle</button>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="discount" class="form-label">Descuento</label>
                    <div class="col-md-12 input-group">
                        <select name="discountType" id="discountType" class="form-select">
                            <option value="1">$</option>
                            <option value="2" selected>%</option>
                        </select>
                        <input type="number" class="form-control" id="discount" value="0.00">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="tax" class="form-label">Recargos / Impuestos</label>
                    <div class="col-md-12 input-group">
                        <select name="taxType" id="taxType" class="form-select">
                            <option value="1">$</option>
                            <option value="2" selected>%</option>
                        </select>
                        <input type="number" class="form-control" id="tax" value="0.00">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="ship" class="form-label">Envío</label>
                    <div class="col-md-12 input-group">
                        <select name="shipMethod" id="shipMethod" class="form-select">
                        </select>
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control col-md-10" id="ship" value="0.00">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="payMethod" class="form-label">Medio de pago</label>
                    <select name="payMethod" id="payMethod" class="form-select">
                        <option value="1">$</option>
                        <option value="2" selected>%</option>
                    </select>
                </div>
            </div>
            <hr class="my-4">
            <h4 class="mb-3">Agregá notas a la venta</h4>
            <div class="col-md-12 mb-5">
                <textarea class="form-control" placeholder="Notas..." id="notes" name="notes" rows="4"></textarea>
            </div>
        </form>
    </div>
</div>
<script>
</script>