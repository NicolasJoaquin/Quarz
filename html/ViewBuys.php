<?php 
// html/ViewBuys.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Visualización y Modificación de Compras"); 
$header->render();

?>


<h1>Visualización y Modificación de Compras</h1>

<div id="main" class="noBloq">
  <input type="search" name="filterValue" id="filterValue" placeholder = "Buscar">
  <input type="button" name="refresh" id="refresh" value="Actualizar">
  <table id="buysTable">    
      <thead> <tr><th>Nro.</th> <th>Usuario</th> <th>Proveedor</th> <th>Total</th> <th>Fecha emisión</th> <th>Envío</th> <th>Pago</th></tr> </thead>
      <tbody id="buysTableBody">

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

    <label for="provider_name">Proveedor: </label>
    <input type="text" readonly name="provider_name" id="provider_name"> <br> 

    <label for="total">Total: </label>
    <input type="text" readonly name="total" id="total"> <br>

    <label for="start_date">Fecha emisión: </label>
    <input type="text" readonly name="start_date" id="start_date"> <br>

    <label for="shipment_state">Envío: </label>
    <select name="shipment_state" id="shipment_state">

    </select> <br>

    <label for="payment_state">Pago: </label>
    <select name="payment_state" id="payment_state">

    </select> <br>

    <label for="description">Notas: </label> <br>
    <textarea name="description" id="description" cols="40" rows="2"></textarea>
  </div>

  <div id="modalFooter">
    <button id="deleteBuy">Borrar</button>
    <button id="updateBuy">Modificar</button>
    <button id="viewDetail">Ver Detalle</button>
  </div>
</div>

<!-- Detalle pedido -->
<div id="modalDetail" class="hidden">
  <div id="modalHeaderDetail">
    <button id="closeModalDetail">Volver</button>
  </div>

  <div id="modalBodyDetail">
    <div id="buyDetail">
      <table id="buyDetailTable">    <!-- SON 7 CAMPOS -->
          <thead> <tr><th>Pos.</th> <th>Cod.</th> <th>Descripción</th> <th>Costo</th> <th>Cantidad</th> <th>Total</th></tr> </thead>
          <tbody id="buyDetailTableBody">
              
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

  function getShipmentStates(buyShipId){
    $("#shipment_state").empty();
    $.get("./viewBuys", {getShipmentStates: true}, function(response) {
      shipStates = JSON.parse(response);
      shipStates.forEach(function(row){
        if(row['shipment_state_id'] == buyShipId){
          $("#shipment_state").append('<option value=' + row['shipment_state_id'] + ' selected>' + row['title'] + '</option>');
        }else{
          $("#shipment_state").append('<option value=' + row['shipment_state_id'] + '>' + row['title'] + '</option>');
        }
      });
    });
  }

  function getPaymentStates(buyShipId){
    $("#payment_state").empty();
    $.get("./viewBuys", {getPaymentStates: true}, function(response) {
      payStates = JSON.parse(response);
      payStates.forEach(function(row){
        if(row['payment_state_id'] == buyShipId){
          $("#payment_state").append('<option value=' + row['payment_state_id'] + ' selected>' + row['title'] + '</option>');
        }else{
          $("#payment_state").append('<option value=' + row['payment_state_id'] + '>' + row['title'] + '</option>');
        }
      });
    });
  }

  function getBuyDetail(buyId){ //ESTOY ACA
    $("#buyDetailTableBody").empty();
    $.get("./viewBuys", {getBuyDetail: true, buy_id: buyId}, function(response) {
      var buyItems = JSON.parse(response);
      buyItems.forEach(function(item){
        $("#buyDetailTableBody").append('<tr id=item' + item.buy_item_id + '></tr>');
        $("#item"+ item.buy_item_id).append('<td>' + item.position + '</td>');
        $("#item"+ item.buy_item_id).append('<td>' + item.product_id + '</td>');
        $("#item"+ item.buy_item_id).append('<td>' + item.description + '</td>');
        $("#item"+ item.buy_item_id).append('<td>' + item.cost_price + '</td>');
        $("#item"+ item.buy_item_id).append('<td>' + item.quantity + '</td>');
        $("#item"+ item.buy_item_id).append('<td>' + item.total_cost + '</td>');
      });
    }); 
  }

  function cleanBuysTable(){
    $("#buysTableBody").empty();
  }

  function cleanDetailTable(){
    $("#buyDetailTableBody").empty();
  }


  function getBuyData(){ //MODIFICAR ESTO, QUE LAS VENTAS ME QUEDEN GUARDADAS EN UN OBJETO
    var buyData = {id : $("#id").val(), pay_id : $("#payment_state").val(), ship_id : $("#shipment_state").val(),
                    description : $("#description").val().trim()};
    return buyData;
  }

  function getBuys(){
    cleanBuysTable();
    var filterValue = $("#filterValue").val();
    $.get("./viewBuys", {getBuys: true, filterValue: filterValue}, function(response) { //AGREGAR FILTER COLUMN Y ORDER
      response = JSON.parse(response);
      response.forEach(function(buy) {
        $("#buysTableBody").append('<tr id=buy' + buy['buy_id'] + '></tr>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['buy_id'] + '</td>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['user_name'] + '</td>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['provider_name'] + '</td>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['total'] + '</td>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['start_date'] + '</td>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['ship_desc'] + '</td>');
        $("#buy" + buy['buy_id']).append('<td>' + buy['pay_desc'] + '</td>');

        $("#buy" + buy['buy_id']).click(function (){  
          // Datos de la compra
          $("#main").removeClass('noBloq').addClass('bloq');
          $("#modal").removeClass('hidden').addClass('show');
          $("#id").val(buy['buy_id']);
          $("#user").val(buy['user_name']);
          $("#provider_name").val(buy['provider_name']);
          $("#total").val(buy['total']);
          $("#start_date").val(buy['start_date']);
          getShipmentStates(buy['ship_id']);
          getPaymentStates(buy['pay_id']);
          $("#description").text(buy['description']);
          // Detalle de la compra
          getBuyDetail(buy['buy_id']);
        });
      });
    });
  }

  getBuys();

  $("#closeModal").click(function(){
      $("#main").removeClass('bloq').addClass('noBloq');
      $("#modal").removeClass('show').addClass('hidden');
  });

  $("#deleteBuy").click(function(){
    var id = $("#id").val();
    if(confirm("¿Seguro de eliminar la compra Nro. " + id + "?")){
      $.post("", {delete: true, buy_id: id}, function(response){
        alert(response); 
      });
    }
    locate("./viewBuys"); 
  });

  $("#updateBuy").click(function(){
    var buy = getBuyData();
    buy = JSON.stringify(buy);
    $.post("", {update: true, buy: buy}, function(response){
      alert(response); 
      locate("./viewBuys"); 
    });
  });

  $("#refresh").click(function(){
    getBuys();
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
