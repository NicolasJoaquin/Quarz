
<div class="row">
    <div class="text-right col-md-6">
        <img class="d-block mx-auto mb-none" src="./extras/quarz-logo.png" alt="" width="72" height="90">
        <h2 class="text-center">Visualización de productos</h2>
    </div>
    <div class="text-right col-md-6 mt-5">
        <p class="lead">
            Podés filtrar los productos por su nombre. <br>
            Hacé click en la fila de cualquier producto para ver su detalle o editarlo. <br>
        </p>
    </div>
</div>
<div class="row g-5">
    <div class="col-md-12 col-lg-12 order-md-last">
        <div class="col-sm-12 mb-4">
            <label for="filter" class="form-label">Filtro</label>
            <input type="text" class="form-control" id="filter" placeholder="Filtre por descripción del producto" value="" required="" >
        </div>
        <div class="table-wrapper scrolleable">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="td-cod">Cod.</th>
                        <th scope="col" class="td-desc">Descripción</th>
                        <!-- <th scope="col">Proveedor</th> -->
                        <th scope="col" class="td-pack">Unidad de empaque</th>
                        <th scope="col" class="td-cost">Costo</th>
                        <th scope="col" class="td-sale-price">Precio venta</th>
                        <th scope="col" class="td-stock">En stock</th>
                        <!-- <th scope="col" class="td-acc">Acciones</th> -->
                    </tr>
                </thead>
                <tbody id="productsTableBody" class="claseprueba">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
</script>