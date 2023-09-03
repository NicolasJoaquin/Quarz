<div class="row mb-3">
    <div class="text-right col-md-6">
        <img class="d-block mx-auto mb-none" src="./extras/quarz-logo.png" alt="" width="72" height="90">
        <h2 class="text-center">Visualización de ventas</h2>
    </div>
    <div class="text-right col-md-6 mt-5">
        <p class="lead">
            FIX <br>
        </p>
    </div>
</div>
<div class="row g-5">
    <div class="col-md-12 col-lg-12 order-md-last">
        <div class="table-wrapper table-responsive">
            <table class="table table-striped table-hover mb-none">
                <thead>
                    <div class="d-flex bd-highlight">
                        <select class="ms-auto mb-2 form-select col-md-4" name="limitLength" id="limitLength">
                            <option value="20" selected>Mostrar de a 20 registros</option>
                            <option value="50">Mostrar de a 50 registros</option>
                            <option value="100">Mostrar de a 100 registros</option>
                            <option value="9999">Mostrar todos los registros</option>
                        </select>
                    </div>
                </thead>
                <thead class="table-dark">
                    <tr class="orders">
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                N°<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="saleOrder" id="saleOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Usuario<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="userOrder" id="userOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Cliente<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="clientOrder" id="clientOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Pres.<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="budgetOrder" id="budgetOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Fecha<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="dateOrder" id="dateOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Envío<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="shipmentOrder" id="shipmentOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Pago<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="paymentOrder" id="paymentOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Total<i class="bi bi-sort-down ms-auto"></i>
                                <!-- <input type="hidden" name="paymentOrder" id="paymentOrder" value="desc"> -->
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Acciones
                            </div>
                        </th>
                    </tr>
                </thead>
                <thead>
                    <tr class="filters">
                        <th scope="col">
                            <input placeholder="#" type="number" class="form-control" name="saleNumberFilter" id="saleNumberFilter">
                        </th>
                        <th scope="col">
                            <input placeholder="Mario Santos" type="text" class="form-control" name="userFilter" id="userFilter">
                        </th>
                        <th scope="col">
                            <input placeholder="Franco Milazzo" type="text" class="form-control" name="clientFilter" id="clientFilter">
                        </th>
                        <th scope="col">
                            <input placeholder="#" type="number" class="form-control" name="budgetFilter" id="budgetFilter">
                        </th>
                        <th scope="col" class="input-group">
                            <input placeholder="Desde" type="text" class="form-control" name="fromDateFilter" id="fromDateFilter">
                            <input placeholder="Hasta" type="text" class="form-control" name="toDateFilter" id="toDateFilter">
                        </th>
                        <th scope="col">
                            <select name="shipmentFilter" id="shipmentFilter" class="form-select">
                                <option value="0" selected>Todos</option>
                            </select>
                        </th>
                        <th scope="col">
                            <select name="paymentFilter" id="paymentFilter" class="form-select">
                                <option value="0" selected>Todos</option>
                            </select>
                        </th>
                        <th scope="col">
                            <input placeholder="$" type="number" class="form-control" name="totalFilter" id="totalFilter">
                        </th>
                    </tr>
                </thead>
                <tbody class="scrolleable" id="salesTableBody">
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="1">
                            Registros
                        </td>
                        <td colspan="1">
                            <span class="d-flex bd-highlight" id="registers"></span>
                        </td>
                        <td colspan="4">
                        </td>
                        <td colspan="1">
                            Total
                        </td>
                        <td colspan="2">
                            <span class="d-flex bd-highlight text-left" id="total"></span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row mt-2">
            <nav class="mb-none d-flex bd-highlight">
                <input type="hidden" name="limitOffset" id="limitOffset" value="0">
                <ul class="pagination ms-auto">
                </ul>
            </nav>
        </div>
    </div>
</div>
