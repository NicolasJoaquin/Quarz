
<div class="text-center">
    <img class="d-block mx-auto mb-4" src="./extras/quarz-logo.png" alt="" width="72" height="90">
    <h2>Formulario de nueva venta</h2>
    <p class="lead">
        AGREGAR TEXTO
    </p>
</div>
<!-- COLUMNA DERECHA -->
<div class="row g-5">
    <div class="col-md-7 col-lg-7 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-primary">Detalle de venta</span>
            <!-- <input class="form-control" type="text" value="Disabled readonly input" aria-label="Disabled input example" disabled readonly> -->
            <span class="badge bg-primary rounded-pill">3</span>
        </h4>
        <div class="col-sm-12 mb-4">
            <label for="firstName" class="form-label">Cliente</label>
            <input type="text" class="form-control" id="client" placeholder="Ingrese un cliente" value="" required="" readonly disabled>
            <!-- <div class="invalid-feedback">
                Se requiere un nombre válido.
            </div> -->
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-1">
            <button type="button" class="btn btn-outline-secondary btn-sm">Limpiar detalle</button>
        </div>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Pos.</th>
                    <th scope="col">Cod.</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Costo</th>
                    <th scope="col">Precio de venta</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Total</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Borrar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                </tr>
            </tbody>
        </table>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Continuar con el pago</button>




        <!-- <ul class="list-group mb-3 mt-2">
            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                    <h6 class="my-0">Nombre del producto</h6>
                    <small class="text-muted">Breve descripción</small>
                </div>
                <span class="text-muted">$12</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                    <h6 class="my-0">Segundo producto</h6>
                    <small class="text-muted">Breve descripción</small>
                </div>
                <span class="text-muted">$8</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                    <h6 class="my-0">Tercer elemento</h6>
                    <small class="text-muted">Breve descripción</small>
                </div>
                <span class="text-muted">$5</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light">
                <div class="text-success">
                    <h6 class="my-0">Código promocional</h6>
                    <small>EXAMPLECODE</small>
                </div>
                <span class="text-success">−$5</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Total ($)</span>
                <strong>$20</strong>
            </li>
        </ul> -->

        <!-- <form class="card p-2">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Código Promo">
                <button type="submit" class="btn btn-secondary">Aplicar</button>
            </div>
        </form> -->
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
            <div class="row g-3">
                <div class="col-sm-12 mb-3">
                    <label for="productToPush" class="form-label">Producto a agregar</label>
                    <select class="form-select" name="productToPush" id="productToPush">
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="productCostToPush" class="form-label">Costo</label>
                    <input type="number" class="form-control" id="productCostToPush" name="productCostToPush" placeholder="">
                    <!-- <div class="invalid-feedback">
                        Selecciona un país válido.
                    </div> -->
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
                    <button class="btn btn-outline-success" type="button">Agregar al detalle</button>
                </div>
            </div>
            <hr class="my-4">
            <h4 class="mb-3">Agregá notas a la venta</h4>
            <div class="col-md-12 mb-5">
                <textarea class="form-control" placeholder="Notas..." id="notes" name="notes"></textarea>
            </div>
        </form>
    </div>
</div>
<script>
</script>