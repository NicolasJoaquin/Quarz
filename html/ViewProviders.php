<?php 
// html/ViewProviders.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Visualización de Proveedores");
$header->render();

?>


<h1>Visualización y Modificación de Proveedores</h1>

<div id="main" class="noBloq">
  <input type="search" name="filterValue" id="filterValue" placeholder = "Buscar">
  <input type="button" name="refresh" id="refresh" value="Actualizar">
  <table id="providersTable">    
      <thead> <tr><th>Razón Social</th> <th>CUIT</th> <th>Nombre Fantasía</th> <th>Dirección</th> <th>Email</th> <th>Teléfono</th></tr> </thead>
      <tbody id="providersTableBody">

      </tbody>
  </table>
</div>
    
<div id="modal" class="hidden">
  <div id="modalHeader">
    <button id="closeModal">Cerrar</button>
  </div>

  <div id="modalBody">
    <label for="id">ID de Proveedor: </label>
    <input type="number" readonly name="id" id="id"> <br>

    <label for="name">Razón Social: </label>
    <input type="text" name="name" id="name"> <br>

    <label for="CUIT">CUIT: </label>
    <input type="number" name="CUIT" id="CUIT"> <br> 

    <label for="nickname">Nombre Fantasía: </label>
    <input type="text" name="nickname" id="nickname"> <br>
    
    <label for="direction">Dirección: </label>
    <input type="text" name="direction" id="direction"> <br>

    <label for="email">Email: </label>
    <input type="text" name="email" id="email"> <br>

    <label for="phone">Teléfono: </label>
    <input type="number" name="phone" id="phone"> <br>
  </div>

  <div id="modalFooter">
    <button id="deleteProvider">Borrar</button>
    <button id="updateProvider">Modificar</button>
  </div>
</div>


<script>
  $(document).ready(function (){
      function locate(url){
          $(location).attr('href',url);
      }

      function cleanTable(){
        $("#providersTableBody").empty();
      }

      function getProviderData(){
        var provider = {id : $("#id").val().trim(), name : $("#name").val().trim(), CUIT : $("#CUIT").val().trim(),
                        nickname : $("#nickname").val().trim(), direction : $("#direction").val().trim(), email : $("#email").val().trim(), 
                        phone : $("#phone").val().trim()};
        return provider;
      }

      function getProviders(){
        cleanTable();
        var filterValue = $("#filterValue").val();
        $.get("./viewProviders", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) { //AGREGAR FILTER COLUMN Y ORDER
          response = JSON.parse(response);
          response.forEach(function(provider) {
            $("#providersTableBody").append('<tr id=' + provider['provider_id'] + '></tr>');
            $("#" + provider['provider_id']).append('<td>' + provider['name'] + '</td>');
            $("#" + provider['provider_id']).append('<td>' + provider['CUIT'] + '</td>');
            $("#" + provider['provider_id']).append('<td>' + provider['nickname'] + '</td>');
            $("#" + provider['provider_id']).append('<td>' + provider['direction'] + '</td>');
            $("#" + provider['provider_id']).append('<td>' + provider['email'] + '</td>');
            $("#" + provider['provider_id']).append('<td>' + provider['phone'] + '</td>');

            $("#" + provider['provider_id']).click(function (){  //
                $("#main").removeClass('noBloq').addClass('bloq');
                $("#modal").removeClass('hidden').addClass('show');
                $("#id").val(provider['provider_id']);
                $("#name").val(provider['name']);
                $("#CUIT").val(provider['CUIT']);
                $("#nickname").val(provider['nickname']);
                $("#direction").val(provider['direction']);
                $("#email").val(provider['email']);
                $("#phone").val(provider['phone']);
            });
          });
        });
      }

      getProviders();  

      $("#closeModal").click(function(){
          $("#main").removeClass('bloq').addClass('noBloq');
          $("#modal").removeClass('show').addClass('hidden');
      });

      $("#deleteProvider").click(function(){
        var id = $("#id").val(); 
        if(confirm("¿Seguro de eliminar al proveedor con id " + id + "?")){
          $.post("", {delete: true, provider_id: id}, function(response){
            alert(response); 
          });
        }
        locate("./viewProviders"); 
      });

      $("#updateProvider").click(function(){
        var provider = getProviderData();
        provider = JSON.stringify(provider);
        $.post("", {update: true, provider: provider}, function(response){
          alert(response); 
          locate("./viewProviders");
        });
      });

      $("#refresh").click(function(){
        getProviders();
      });
  });
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
