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
        <input type="hidden" name="id" id="id" value="<?php echo $this->changes[-1]['product_id'] ?>">
        <!-- <div class="col-sm-12 mb-4">
            <label for="filter" class="form-label">Filtro</label>
            <input type="text" class="form-control" id="filter" placeholder="Filtre por usuario" value="" required="" >
        </div> -->
        <div class="table-wrapper">
            <table class="table table-striped table-hover mb-none">
                <thead class="table-dark">
                    <tr class="orders">
                        <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">N°<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="saleOrder" id="saleOrder" value="desc"></div></th>
                        <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Usuario<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="userOrder" id="userOrder" value="desc"></div></th>
                        <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Cliente<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="clientOrder" id="clientOrder" value="desc"></div></th>
                        <th scope="col" class="col-md-1"><div class="d-flex bd-highlight">Pres.<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="budgetOrder" id="budgetOrder" value="desc"></div></th>
                        <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Fecha<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="dateOrder" id="dateOrder" value="desc"></div></th>
                        <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Envío<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="shipmentOrder" id="shipmentOrder" value="desc"></div></th>
                        <th scope="col" class="col-md-2"><div class="d-flex bd-highlight">Pago<i class="bi bi-sort-down ms-auto"></i><input type="hidden" name="paymentOrder" id="paymentOrder" value="desc"></div></th>
                    </tr>
                </thead>
                <thead>
                    <tr class="filters">
                        <th scope="col" class="col-md-1"><input placeholder="#" type="number" class="form-control" name="saleNumberFilter" id="saleNumberFilter"></th>
                        <th scope="col" class="col-md-2"><input placeholder="Mario Santos" type="text" class="form-control" name="userFilter" id="userFilter"></th>
                        <th scope="col" class="col-md-2"><input placeholder="Franco Milazzo" type="text" class="form-control" name="clientFilter" id="clientFilter"></th>
                        <th scope="col" class="col-md-1"><input placeholder="#" type="number" class="form-control" name="budgetFilter" id="budgetFilter"></th>
                        <th scope="col" class="col-md-2 input-group"><input placeholder="Desde" type="text" class="form-control" name="fromDateFilter" id="fromDateFilter"><input placeholder="Hasta" type="text" class="form-control" name="toDateFilter" id="toDateFilter"></th>
                        <th scope="col" class="col-md-2"><select name="shipmentFilter" id="shipmentFilter" class="form-select"><option value="0" selected>Todos</option></select></th>
                        <th scope="col" class="col-md-2"><select name="paymentFilter" id="paymentFilter" class="form-select"><option value="0" selected>Todos</option></select></th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="table-wrapper scrolleable mb-3">
            <table class="table table-striped table-hover">
                <tbody id="salesTableBody">
                </tbody>
            </table>
        </div>

        <div class="row d-flex bd-highlight">
          <nav class="mb-none">
            <ul class="pagination">
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>

    </div>
</div>

