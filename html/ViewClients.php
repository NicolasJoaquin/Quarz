<div class="row mb-3">
    <div class="text-right col-md-6">
        <img class="d-block mx-auto mb-none" src="./extras/quarz-logo.png" alt="" width="72" height="90">
        <h2 class="text-center">Visualización de clientes</h2>
    </div>
    <div class="text-right col-md-6 mt-5">
        <p class="lead">
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
                                <input type="hidden" name="numberOrder" id="numberOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Nombre<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="nameOrder" id="nameOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                DNI<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="dniOrder" id="dniOrder" value="desc">
                            </div>
                        </th>
                        <th scope="col">
                            <div class="d-flex bd-highlight">
                                Mail<i class="bi bi-sort-down ms-auto"></i>
                                <input type="hidden" name="emailOrder" id="emailOrder" value="desc">
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
                            <input placeholder="#" type="number" class="form-control" name="numberFilter" id="numberFilter">
                        </th>
                        <th scope="col">
                            <input placeholder="Mario Santos" type="text" class="form-control" name="nameFilter" id="nameFilter">
                        </th>
                        <th scope="col">
                            <input placeholder="41146999" type="text" class="form-control" name="dniFilter" id="dniFilter">
                        </th>
                        <th scope="col">
                            <input placeholder="quarz@resinaepoxi.com.ar" type="text" class="form-control" name="emailFilter" id="emailFilter">
                        </th>
                    </tr>
                </thead>
                <tbody class="scrolleable" id="tableBody">
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="1">
                            Registros
                        </td>
                        <td colspan="1">
                            <span class="d-flex bd-highlight" id="registers"></span>
                        </td>
                        <td colspan="5">
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