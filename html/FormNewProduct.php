<?php 
// html/FormNewClient.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Nuevo Producto");
$header->render();

?>
<h1>Formulario de alta de producto</h1>
<form action="./newProduct" method="POST">
    <label for="description">Ingrese la descripción: </label>
    <input type="text" name="description" id="description"> <br>

    <label for="cost_price">Ingrese el costo: </label>
    <input type="number" name="cost_price" id="cost_price"> <br>

    <label for="packing_unit">Ingrese la unidad de empaque/venta: </label>
    <input type="text" name="packing_unit" id="packing_unit"> <br>

    <label for="provider">Seleccione un proveedor: </label> <br>
    <select name="provider" id="provider">

    </select> <br>

    <input type="button" name="submit" id="submit" value="Crear">
</form>

<script>
    $(document).ready(function (){
        function locate(url){
            $(location).attr('href',url);
        }

        function getProviders(){
            //NO HACE FALTA HACER CLEAN
            $.get("./viewProviders", {get: true, filterValue: ""}, function(response) { //PODER AGREGAR FILTROS ACA
                response = JSON.parse(response);
                response.forEach(function(provider) {
                    $("#provider").append('<option value=' + provider['provider_id'] + '>' + provider['name'] + '</option>');
                });
            });
        }

        function getProductData(){
            var product = {description : $("#description").val().trim(), cost_price : $("#cost_price").val().trim(),
                            packing_unit : $("#packing_unit").val().trim(), provider_id : $("#provider").val().trim()};
            return product;
        }
        
        function validateForm(){
            if($("#description").val().length < 4){ 
                alert("La descripción del producto debe tener más de 4 caracteres");
                return false;
            }

            if($("#cost_price").val().length === 0){
                alert("Debe ingresar el costo del producto");
                return false;
            }

            if($("#packing_unit").val().length < 3){
                alert("La unidad de empaque del producto debe tener más de 3 caracteres");
                return false;
            }

            if($("#provider").val() == null || $("#provider").val().trim() == ""){
                alert("Debe asignar un proveedor al producto");
                return false;
            }
            return true;
        }

        $("#submit").click(function(){
            if(validateForm()){
                var product = getProductData();
                product = JSON.stringify(product);
                $.post("./newProduct", {new: true, product: product}, function(response){
                    // VER QUE PONGO ACÁ
                    alert(response);
                    console.log(response);
                    locate("");
                });
            }
        });

        getProviders();
    });
</script>   

<?php
$footer = new StdFooter();
$footer->render();
?>
