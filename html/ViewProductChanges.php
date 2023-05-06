<div class="row">
    <div class="text-right col-md-6">
        <img class="d-block mx-auto mb-none" src="./extras/quarz-logo.png" alt="" width="72" height="90">
        <h2 class="text-center">Visualización de cambios de producto <br> #<?php echo $this->changes[-1]['product_id'] . ". " . $this->changes[-1]['product_name'] ?></h2>
    </div>
    <div class="text-right col-md-6 mt-5">
        <p class="lead">
            Podés filtrar y ordenar los cambios por usuario o fecha. <br>
        </p>
    </div>
</div>
<div class="row g-5">
    <div class="col-md-12 col-lg-12 order-md-last">
        <input type="hidden" name="id" id="id" value="<?php echo $this->changes[-1]['product_id'] ?>">
        <div class="col-sm-12 mb-4">
            <label for="filter" class="form-label">Filtro</label>
            <input type="text" class="form-control" id="filter" placeholder="Filtre por usuario" value="" required="" >
        </div>
        <div class="table-wrapper">
            <table class="table table-striped table-hover mb-none">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="col-md-4">Usuario</th>
                        <th scope="col" class="col-md-4">Costo</th>
                        <th scope="col" class="col-md-4">Fecha</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="table-wrapper scrolleable mb-3">
            <table class="table table-striped table-hover">
                <tbody id="changesTableBody">
<?php
foreach($this->changes as $k => $change) {
    if($k == -1)
        continue;
?>  
                    <tr id="<?php echo $change['product_change_id'] ?>">
                        <td class="col-md-4"><?php echo $change['user_name'] ?></td>
                        <td class="col-md-4">$<?php echo $change['cost_price'] ?>
<?php 
$costDif = $change['cost_price'] - $change['old_cost_price'];
if($costDif < 0) {
    $costDif = $costDif * -1;
?>
                            <p class='text-danger'>(<i class='bi bi-arrow-down-circle'></i> $<?php echo $costDif ?>)</p>
<?php
} else {
?>
                            <p class='text-success'>(<i class='bi bi-arrow-up-circle'></i> $<?php echo $costDif ?>)</p>
<?php
}
?>
                        </td>
                        <td class="col-md-4"><?php echo $change['date'] ?></td>
                    </tr>
<?php
}
?>
                </tbody>
            </table>
        </div>
        <div class="col-2">
            <button class="btn btn-primary" type="button" id="goBack">
                <i class="bi bi-arrow-bar-left"></i>
                Volver
            </button>
        </div>
    </div>
</div>