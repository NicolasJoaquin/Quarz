<?php 
// html/ViewProducts.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Visualización y Modificación de Productos");
$header->render();

?>


<h1>Visualización y Modificación de Productos</h1>

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
    <input type="text" name="description" id="description"> <br>

    <label for="cost_price">Costo: </label>
    <input type="number" name="cost_price" id="cost_price"> <br> 

    <label for="packing_unit">Unidad: </label>
    <input type="text" name="packing_unit" id="packing_unit"> <br>

    <label for="provider">Proveedor: </label> <br>
    <select name="provider" id="provider">

    </select> <br>
  </div>

  <div id="modalFooter">
    <!-- <button id="deleteProduct">Borrar</button> HASTA VERIFICAR EL BORRADO DE DATOS FORANEOS-->
    <button id="updateProduct">Modificar</button>
  </div>
</div>


<script>
$(document).ready(function (){
  function locate(url){
    $(location).attr('href',url);
  }

  function validateForm(){
    if($("#description").val().length === 0){
        alert("Falta la descripción del producto");
        return false;
    }
    if($("#cost_price").val().length === 0){
        alert("Falta el costo del producto");
        return false;
    }
    if($("#packing_unit").val().length === 0){
        alert("Falta la unidad de empaque del producto");
        return false;
    }
    if($("#provider").val().length === 0){
        alert("Falta asignar un proveedor al producto");
        return false;
    }
    return true;
  }

  function cleanTable(){
    $("#productsTableBody").empty();
  }

  function getProductData(){
    var product = {id : $("#id").val().trim(), description : $("#description").val().trim(), cost_price : $("#cost_price").val().trim(),
                        packing_unit : $("#packing_unit").val().trim(), provider_id : $("#provider").val().trim()};
    return product;
  }


  function getProviders(providerId){
    $("#provider").empty();
    $.get("./viewProviders", {get: true}, function(response) {
      providers = JSON.parse(response);
      providers.forEach(function(provider){
        if(provider['provider_id'] == providerId){
          $("#provider").append('<option value=' + provider['provider_id'] + ' selected>' + provider['name'] + '</option>');
        }else{
          $("#provider").append('<option value=' + provider['provider_id'] + '>' + provider['name'] + '</option>');
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

  $("#deleteProduct").click(function(){
    var id = $("#id").val();
    if(confirm("¿Seguro de eliminar el producto con código " + id + "?")){
      $.post("", {delete: true, product_id: id}, function(response){
        alert(response); 
      });
    }
    locate("./viewProducts"); 
  });

  $("#updateProduct").click(function(){ //TOY ACA
    // AGREGAR VALIDACION FRONT
    if(validateForm()){
      var product = getProductData();
      product = JSON.stringify(product);
      $.post("", {update: true, product: product}, function(response){
        alert(response); 
        locate("./viewProducts");
      });
    }
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
