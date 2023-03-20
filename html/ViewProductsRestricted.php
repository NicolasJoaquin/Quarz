<?php 
// html/ViewProducts.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Visualización y Modificación de Productos");
$header->render();

?>
<h1>Visualización de Productos</h1>

<div id="main" class="noBloq">
  <input type="search" name="filterValue" id="filterValue" placeholder = "Buscar">
  <input type="button" name="refresh" id="refresh" value="Actualizar">
  <table id="productsTable">    
      <thead> <tr><th>Código</th> <th>Descripción</th> <th>Costo</th> <th>Unidad</th></tr> </thead>
      <tbody id="productsTableBody">

      </tbody>
  </table>
</div>
    
<div id="modal" class="hidden">
  <div id="modalHeader">
    <button id="closeModal">Cerrar</button>
  </div>

  <div id="modalBody">
    <label for="id">Código: </label>
    <input type="number" readonly name="id" id="id"> <br>

    <label for="description">Descripción: </label>
    <input type="text" readonly name="description" id="description"> <br>

    <label for="cost_price">Costo: </label>
    <input type="number" readonly name="cost_price" id="cost_price"> <br> 

    <label for="packing_unit">Unidad: </label>
    <input type="text" readonly name="packing_unit" id="packing_unit"> <br>

    <label for="provider">Proveedor: </label> <br> <!-- MODIFICAR ACA -->
    <input type="text" readonly name="provider" id="provider"> <br>

  </div>

  <div id="modalFooter">

  </div>
</div>


<script>
$(document).ready(function (){
  function locate(url){
    $(location).attr('href',url);
  }

  function cleanTable(){
    $("#productsTableBody").empty();
  }

  function getProviders(providerId){
    $("#provider").val("");
    $.get("./viewProviders", {get: true}, function(response) {
      providers = JSON.parse(response);
      providers.forEach(function(provider){
        if(provider['provider_id'] == providerId){
          $("#provider").val(provider['name']);
        }
      });
    });
  }

  function getProducts(){
    cleanTable();
    var filterValue = $("#filterValue").val();
    $.get("./viewProducts", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) { //AGREGAR FILTER COLUMN Y ORDER
      response = JSON.parse(response);
      response.forEach(function(product) {
        $("#productsTableBody").append('<tr id=' + product['product_id'] + '></tr>');
        $("#" + product['product_id']).append('<td>' + product['product_id'] + '</td>');
        $("#" + product['product_id']).append('<td>' + product['description'] + '</td>');
        $("#" + product['product_id']).append('<td>' + product['cost_price'] + '</td>');
        $("#" + product['product_id']).append('<td>' + product['packing_unit'] + '</td>');

        $("#" + product['product_id']).click(function (){  
            $("#main").removeClass('noBloq').addClass('bloq');
            $("#modal").removeClass('hidden').addClass('show');
            $("#id").val(product['product_id']);
            $("#description").val(product['description']);
            $("#cost_price").val(product['cost_price']);
            $("#packing_unit").val(product['packing_unit']);
            getProviders(product['provider_id']);
        });
      });
    });
  }

  getProducts();

  $("#closeModal").click(function(){
      $("#main").removeClass('bloq').addClass('noBloq');
      $("#modal").removeClass('show').addClass('hidden');
  });

  $("#refresh").click(function(){
    getProducts();
  });
});
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
