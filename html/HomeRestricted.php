<?php 
// html/HomeRestricted.php
// require_once '../views/StdHeader.php'; 
// require_once '../views/StdFooter.php';

// $header = new StdHeader("Home");
// $header->render();

?>
<div id="homeDiv">
    <div><!--Header--> 
        <h1>Bienvenido: <?= htmlentities($_SESSION['user']) ?> </h1>
    </div>

    <div><!--Visualización y modificación de pedidos--> 
        <h3>Gestión de pedidos</h3>
        <button id="newSale">Nueva venta/pedido</button>
        <button id="viewSales">Ver ventas/pedidos</button>
    </div>

    <div><!--Visualización y modificación de productos--> 
        <h3>Gestión de productos</h3>
        <button id="viewProducts">Ver productos</button>
    </div>

    <div><!--Visualización y modificación del stock--> 
        <h3>Gestión de stock</h3>
        <button id="viewStock">Ver stock</button>
    </div>

    <div><!--Gestión de clientes--> 
        <h3>Gestión de clientes</h3>
        <button id="newClient">Nuevo Cliente</button>
        <button id="viewClients">Ver Clientes</button>
    </div>

    <div><!--Footer--> 
        <h4>Su nivel de permisos: <?= $_SESSION['perm'] ?> </h4>
        <button id="closeSession">Cerrar Sesion</button>
    </div>
</div>

     
<script>
$(document).ready(function (){
    function locate(url){
        $(location).attr('href',url);
    }

    $("#newSale").click(function (){
        var url = "./newSale";
        locate(url);
    });

    $("#viewSales").click(function (){
        var url = "./viewSales";
        locate(url);
    });

    $("#viewProducts").click(function (){
        var url = "./viewProducts";
        locate(url);
    });

    $("#newClient").click(function (){
        var url = "./newClient";
        locate(url);
    });

    $("#viewClients").click(function (){
        var url = "./viewClients";
        locate(url);
    });

    $("#closeSession").click(function (){
        var url = "./closeSession";
        locate(url);
    });

    $("#viewStock").click(function (){
        var url = "./viewStock";
        locate(url);
    });            
});
</script>

<?php
$footer = new StdFooter();
$footer->render();
?>

