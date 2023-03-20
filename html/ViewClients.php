<?php 
// html/ViewClients.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Visualización y Modificación de Clientes");
$header->render();

?>


<h1>Visualización y Modificación de Clientes</h1>

<div id="main" class="noBloq">
  <input type="search" name="filterValue" id="filterValue" placeholder = "Buscar">
  <input type="button" name="refresh" id="refresh" value="Actualizar">
  <table id="clientsTable">    
      <thead> <tr><th>Razón Social</th> <th>CUIT</th> <th>Nombre Fantasía</th> <th>Dirección</th> <th>Email</th> <th>Teléfono</th></tr> </thead>
      <tbody id="clientsTableBody">

      </tbody>
  </table>
</div>
    
<div id="modal" class="hidden">
  <div id="modalHeader">
    <button id="closeModal">Cerrar</button>
  </div>

  <div id="modalBody">
    <label for="id">ID de Cliente: </label>
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
    <button id="deleteClient">Borrar</button>
    <button id="updateClient">Modificar</button>
  </div>
</div>


<script>
  $(document).ready(function (){
      function locate(url){
          $(location).attr('href',url);
      }

      function cleanTable(){
        $("#clientsTableBody").empty();
      }

      function getClientData(){
        var client = {id : $("#id").val().trim(), name : $("#name").val().trim(), CUIT : $("#CUIT").val().trim(),
                          nickname : $("#nickname").val().trim(), direction : $("#direction").val().trim(), email : $("#email").val().trim(),
                          phone : $("#phone").val().trim()};
        return client;
      }

      function getClients(){
        cleanTable();
        var filterValue = $("#filterValue").val();
        $.get("./viewClients", {get: true, filterColumn: false, filterValue: filterValue, order: false}, function(response) { //AGREGAR FILTER COLUMN Y ORDER
          response = JSON.parse(response);
          response.forEach(function(client) {
            $("#clientsTableBody").append('<tr id=' + client['client_id'] + '></tr>');
            $("#" + client['client_id']).append('<td>' + client['name'] + '</td>');
            $("#" + client['client_id']).append('<td>' + client['CUIT'] + '</td>');
            $("#" + client['client_id']).append('<td>' + client['nickname'] + '</td>');
            $("#" + client['client_id']).append('<td>' + client['direction'] + '</td>');
            $("#" + client['client_id']).append('<td>' + client['email'] + '</td>');
            $("#" + client['client_id']).append('<td>' + client['phone'] + '</td>');

            $("#" + client['client_id']).click(function (){  //
                $("#main").removeClass('noBloq').addClass('bloq');
                $("#modal").removeClass('hidden').addClass('show');
                $("#id").val(client['client_id']);
                $("#name").val(client['name']);
                $("#CUIT").val(client['CUIT']);
                $("#nickname").val(client['nickname']);
                $("#direction").val(client['direction']);
                $("#email").val(client['email']);
                $("#phone").val(client['phone']);
            });
          });
        });
      }

      getClients();

      $("#closeModal").click(function(){
          $("#main").removeClass('bloq').addClass('noBloq');
          $("#modal").removeClass('show').addClass('hidden');
      });

      $("#deleteClient").click(function(){ // VER COMO TENER EN CUENTA LA ELIMINACIÓN DE DATOS FORANEOS DEL CLIENTE A ELIMINAR
        var id = $("#id").val();
        if(confirm("¿Seguro de eliminar al cliente con id " + id + "?")){
          $.post("", {delete: "", client_id: id}, function(response){
            alert(response); 
          });
        }
        locate("./viewClients"); // CAMBIAR POR getClients(); 
      });

      $("#updateClient").click(function(){
        var client = getClientData();
        client = JSON.stringify(client);
        $.post("", {update: "", client: client}, function(response){
          alert(response); // BORRAR
          locate("./viewClients");
        });
      });

      $("#refresh").click(function(){
        getClients();
      });
  });
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
