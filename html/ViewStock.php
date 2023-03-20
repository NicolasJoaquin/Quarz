<?php 
// html/ViewStock.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Ver Stock");
$header->render();

?>


<h1>Visualización y Ajuste de Stock</h1>

<div id="main" class="noBloq">
  <input type="search" name="filterValue" id="filterValue" placeholder = "Buscar">
  <input type="button" name="refresh" id="refresh" value="Actualizar">
  <table id="stockTable">    
      <thead> <tr><th>Código Producto</th> <th>Descripción</th> <th>Depósito</th> <th>Cantidad</th></tr> </thead>
      <tbody id="stockTableBody">

      </tbody>
  </table>
</div>
    
<div id="modal" class="hidden">
  <div id="modalHeader">
    <button id="closeModal">Cerrar</button>
  </div>

  <div id="modalBody">
    <label for="id">Código Stock: </label>
    <input type="number" readonly name="id" id="id"> <br> <!-- ver esto -->

    <label for="product_id">Código Producto: </label>
    <input type="number" readonly name="product_id" id="product_id"> <br>

    <label for="product_name">Descripción: </label>
    <input type="text" readonly name="product_name" id="product_name"> <br>

    <label for="warehouse_name">Depósito: </label>
    <input type="text" readonly name="warehouse_name" id="warehouse_name"> <br>

    <label for="quantity">Cantidad: </label>
    <input type="number" name="quantity" id="quantity"> <br>
  </div>

  <div id="modalFooter">
    <button id="updateItem">Modificar</button>
  </div>
</div>


<script>
  $(document).ready(function (){
      function locate(url){
          $(location).attr('href',url);
      }

      function cleanTable(){
        $("#stockTableBody").empty();
      }

      function getItemData(){
        var item = {id : $("#id").val(), quantity : $("#quantity").val().trim()};
        return item;
      }

      function getItems(){
        cleanTable();
        var filterValue = $("#filterValue").val();
        $.get("./viewStock", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) { //AGREGAR FILTER COLUMN Y ORDER
          response = JSON.parse(response);
          response.forEach(function(item) {
            $("#stockTableBody").append('<tr id=' + item['stock_id'] + '></tr>');
            $("#" + item['stock_id']).append('<td>' + item['product_id'] + '</td>');
            $("#" + item['stock_id']).append('<td>' + item['product_name'] + '</td>');
            $("#" + item['stock_id']).append('<td>' + item['warehouse_name'] + '</td>');
            $("#" + item['stock_id']).append('<td>' + item['quantity'] + '</td>');

            $("#" + item['stock_id']).click(function (){ 
                $("#main").removeClass('noBloq').addClass('bloq');
                $("#modal").removeClass('hidden').addClass('show');
                $("#id").val(item['stock_id']);
                $("#product_id").val(item['product_id']);
                $("#product_name").val(item['product_name']);
                $("#warehouse_name").val(item['warehouse_name']);
                $("#quantity").val(item['quantity']);
            });
          });
        });
      }

      getItems();

      $("#closeModal").click(function(){
          $("#main").removeClass('bloq').addClass('noBloq');
          $("#modal").removeClass('show').addClass('hidden');
      });

      $("#updateItem").click(function(){
        var item = getItemData();
        item = JSON.stringify(item);
        $.post("", {update: true, item: item}, function(response){
          alert(response); 
          locate("./viewStock");
        });
      });

      $("#refresh").click(function(){
        getItems();
      });
  });
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
