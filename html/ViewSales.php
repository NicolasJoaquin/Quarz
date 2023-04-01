<?php 
// html/ViewSales.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Visualización y Modificación de Ventas");
$header->render();

?>


<h1>Visualización y Modificación de Ventas</h1>

<div id="main" class="noBloq">
  <input type="search" name="filterValue" id="filterValue" placeholder = "Buscar">
  <input type="button" name="refresh" id="refresh" value="Actualizar">
  <table id="salesTable">    
      <thead> <tr><th>Nro.</th> <th>Usuario</th> <th>Cliente</th> <th>Total</th> <th>Fecha emisión</th> <th>Envío</th> <th>Pago</th></tr> </thead>
      <tbody id="salesTableBody">

      </tbody>
  </table>
</div>
    
<div id="modal" class="hidden">
  <div id="modalHeader">
    <button id="closeModal">Cerrar</button>
  </div>

  <div id="modalBody">
    <label for="id">Nro.: </label>
    <input type="number" readonly name="id" id="id"> <br>

    <label for="user">Usuario: </label>
    <input type="text" readonly name="user" id="user"> <br>

    <label for="client_name">Cliente: </label>
    <input type="text" readonly name="client_name" id="client_name"> <br> 

    <label for="total">Total: </label>
    <input type="text" readonly name="total" id="total"> <br>

    <label for="start_date">Fecha emisión: </label>
    <input type="text" readonly name="start_date" id="start_date"> <br>

    <label for="shipment_state">Envío: </label>
    <select name="shipment_state" id="shipment_state">

    </select> <br>
    <!-- <input type="text" readonly name="ship_desc" id="ship_desc"> <br> -->

    <label for="payment_state">Pago: </label>
    <select name="payment_state" id="payment_state">

    </select> <br>
    <!-- <input type="text" readonly name="pay_desc" id="pay_desc"> <br> -->

    <label for="description">Notas: </label> <br>
    <textarea name="description" id="description" cols="40" rows="2"></textarea>
    <!-- <input type="text" readonly name="description" id="description"> <br> -->
  </div>

  <div id="modalFooter">
    <button id="deleteSale">Borrar</button>
    <button id="updateSale">Modificar</button>
    <button id="viewDetail">Ver Detalle</button>
  </div>
</div>

<!-- Detalle pedido -->
<div id="modalDetail" class="hidden">
  <div id="modalHeaderDetail">
    <button id="closeModalDetail">Volver</button>
  </div>

  <div id="modalBodyDetail">
    <div id="saleDetail">
      <table id="saleDetailTable">    <!-- SON 7 CAMPOS -->
          <thead> <tr><th>Pos.</th> <th>Cod.</th> <th>Descripción</th> <th>Costo</th> <th>Precio de Venta</th> <th>Cantidad</th> <th>Total</th></tr> </thead>
          <tbody id="saleDetailTableBody">
              
          </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function (){
  function locate(url){
      $(location).attr('href',url);
  }

  function getShipmentStates(saleShipId){
    $("#shipment_state").empty();
    $.get("./viewSales", {getShipmentStates: true}, function(response) {
      shipStates = JSON.parse(response);
      shipStates.forEach(function(row){
        if(row['shipment_state_id'] == saleShipId){
          $("#shipment_state").append('<option value=' + row['shipment_state_id'] + ' selected>' + row['title'] + '</option>');
        }else{
          $("#shipment_state").append('<option value=' + row['shipment_state_id'] + '>' + row['title'] + '</option>');
        }
      });
    });
  }

  function getPaymentStates(salePayId){
    $("#payment_state").empty();
    $.get("./viewSales", {getPaymentStates: true}, function(response) {
      payStates = JSON.parse(response);
      payStates.forEach(function(row){
        if(row['payment_state_id'] == salePayId){
          $("#payment_state").append('<option value=' + row['payment_state_id'] + ' selected>' + row['title'] + '</option>');
        }else{
          $("#payment_state").append('<option value=' + row['payment_state_id'] + '>' + row['title'] + '</option>');
        }
      });
    });
  }

  function getSaleDetail(sale_id){
    $("#saleDetailTableBody").empty();
    $.get("./viewSales", {getSaleDetail: true, sale_id: sale_id}, function(response) {
      var saleItems = JSON.parse(response);
      saleItems.forEach(function(item){
        $("#saleDetailTableBody").append('<tr id=item' + item.sale_item_id + '></tr>');
        $("#item"+ item.sale_item_id).append('<td>' + item.position + '</td>');
        $("#item"+ item.sale_item_id).append('<td>' + item.product_id + '</td>');
        $("#item"+ item.sale_item_id).append('<td>' + item.description + '</td>');
        $("#item"+ item.sale_item_id).append('<td>' + item.cost_price + '</td>');
        $("#item"+ item.sale_item_id).append('<td>' + item.sale_price + '</td>');
        $("#item"+ item.sale_item_id).append('<td>' + item.quantity + '</td>');
        $("#item"+ item.sale_item_id).append('<td>' + item.total_price + '</td>');
      });
    }); 
  }

  function cleanSalesTable(){
    $("#salesTableBody").empty();
  }

  function cleanDetailTable(){
    $("#saleDetailTableBody").empty();
  }


  function getSaleData(){ //MODIFICAR ESTO, QUE LAS VENTAS ME QUEDEN GUARDADAS EN UN OBJETO
    var saleData = {id : $("#id").val(), pay_id : $("#payment_state").val(), ship_id : $("#shipment_state").val(),
                    description : $("#description").val().trim()};
    return saleData;
  }

  function getSales(){
    cleanSalesTable();
    var filterValue = $("#filterValue").val();
    $.get("./viewSales", {getSales: true, filterValue: filterValue}, function(response) { //AGREGAR FILTER COLUMN Y ORDER
      response = JSON.parse(response);
      response.forEach(function(sale) {
        $("#salesTableBody").append('<tr id=sale' + sale['sale_id'] + '></tr>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['sale_id'] + '</td>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['user_name'] + '</td>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['client_name'] + '</td>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['total'] + '</td>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['start_date'] + '</td>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['ship_desc'] + '</td>');
        $("#sale" + sale['sale_id']).append('<td>' + sale['pay_desc'] + '</td>');

        $("#sale" + sale['sale_id']).click(function (){  
          // Datos del pedido
          $("#main").removeClass('noBloq').addClass('bloq');
          $("#modal").removeClass('hidden').addClass('show');
          $("#id").val(sale['sale_id']);
          $("#user").val(sale['user_name']);
          $("#client_name").val(sale['client_name']);
          $("#total").val(sale['total']);
          $("#start_date").val(sale['start_date']);
          getShipmentStates(sale['ship_id']);
          getPaymentStates(sale['pay_id']);
          $("#description").text(sale['description']);
          // Detalle del pedido
          getSaleDetail(sale['sale_id']);
        });
      });
    });
  }

  getSales();

  $("#closeModal").click(function(){
      $("#main").removeClass('bloq').addClass('noBloq');
      $("#modal").removeClass('show').addClass('hidden');
  });

  $("#deleteSale").click(function(){
    var id = $("#id").val();
    if(confirm("¿Seguro de eliminar la venta Nro. " + id + "?")){
      $.post("", {delete: true, sale_id: id}, function(response){
        alert(response); 
      });
    }
    locate("./viewSales"); 
  });

  $("#updateSale").click(function(){
    var sale = getSaleData();
    sale = JSON.stringify(sale);
    $.post("", {update: true, sale: sale}, function(response){
      alert(response); 
      locate("./viewSales"); 
    });
  });

  $("#refresh").click(function(){
    getSales();
  });

  $("#viewDetail").click(function(){
    $("#modal").removeClass('show').addClass('hidden');
    $("#modalDetail").removeClass('hidden').addClass('show');
  });

  $("#closeModalDetail").click(function(){
    $("#modalDetail").removeClass('show').addClass('hidden');
    $("#modal").removeClass('hidden').addClass('show');
  });
  
});
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
