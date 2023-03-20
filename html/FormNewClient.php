<?php 
// html/FormNewClient.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Nuevo Cliente");
$header->render(); //FALTA MODIFICAR ESTO

?>
<h1>Formulario de alta de cliente</h1>
<form action="./newClient" method="POST">
    <label for="name">Ingrese la Razón Social del cliente: </label>
    <input type="text" name="name" id="name"> <br>

    <label for="CUIT">Ingrese el CUIT: </label>
    <input type="text" name="CUIT" id="CUIT"> <br>

    <label for="nickname">Ingrese el nombre de fantasía o apodo del cliente: </label>
    <input type="text" name="nickname" id="nickname"> <br>

    <label for="direction">Ingrese la dirección: </label>
    <input type="text" name="direction" id="direction"> <br>

    <label for="email">Ingrese un email de contacto: </label>
    <input type="text" name="email" id="email"><br>

    <label for="phone">Ingrese un teléfono de contacto: </label>
    <input type="text" name="phone" id="phone"><br>

    <input type="submit" value="Crear">
</form>

<script>
    $(document).ready(function (){
        function locate(url){
            $(location).attr('href',url);
        }
    });
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
