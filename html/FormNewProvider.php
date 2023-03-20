<?php 
// html/FormNewProvider.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Nuevo Proveedor");
$header->render();

?>
<h1>Formulario de alta de proveedor</h1>
<form> 
    <label for="name">Ingrese la Razón Social del proveedor: </label>
    <input type="text" name="name" id="name"> <br>

    <label for="CUIT">Ingrese el CUIT: </label>
    <input type="text" name="CUIT" id="CUIT"> <br>

    <label for="nickname">Ingrese el nombre de fantasía o apodo del proveedor: </label>
    <input type="text" name="nickname" id="nickname"> <br>

    <label for="direction">Ingrese la dirección: </label>
    <input type="text" name="direction" id="direction"> <br>

    <label for="email">Ingrese un email de contacto: </label>
    <input type="text" name="email" id="email"><br>

    <label for="phone">Ingrese un teléfono de contacto: </label>
    <input type="text" name="phone" id="phone"><br>

    <input type="button" name="submit" id="submit" value="Crear">
</form>

<script>
$(document).ready(function (){
    function locate(url){
        $(location).attr('href',url);
    }

    function getProviderData(){
        var provider = {name : $("#name").val().trim(), CUIT : $("#CUIT").val().trim(), nickname : $("#nickname").val().trim(), direction : $("#direction").val().trim(),
            email : $("#email").val().trim(), phone : $("#phone").val().trim()};
        return provider;
    }
    
    function validateForm(){
        if($("#name").val().length < 3){ 
            alert("La razón social debe tener más de 3 caracteres");
            return false;
        }
        return true;
    }

    $("#submit").click(function(){
        if(validateForm()){
            var provider = getProviderData();
            provider = JSON.stringify(provider);
            $.post("./newProvider", {new: true, provider: provider}, function(response){
                // VER QUE PONGO ACÁ
                alert(response);
                console.log(response);
                locate("");
            });
        }
    });
});
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
