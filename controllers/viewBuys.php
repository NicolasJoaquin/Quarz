<?php 
//controllers/viewBuys.php

require_once '../controllers/BuyController.php';

session_start(); 
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new BuyController();

if(count($_POST)>0){
    if(!isset($_SESSION['perm'])) die ("error 0 controllers/viewBuys");
    if($_SESSION['perm'] != 1){     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
        echo "No tiene permiso para modificar y actualizar los compras";
        exit();
    } 
    if(isset($_POST['delete'])){
        //QUIEREN BORRAR UNA COMPRA
        if(!isset($_POST['buy_id'])) die("error 1 controllers/viewBuys DELETE");
        $buy_id = $_POST['buy_id']; 

        echo $controller->deleteBuy($buy_id);

        // if($controller->deleteBuy($buy_id)){ //MODIFICAR ACA PARA QUE PASE EL MESAJE DE ERROR DEL CONTROL DE EXCEPCIONES
        //     $msg = "Se eliminó con éxito el pedido Nro. " . $sale_id . ".";
        //     echo $msg;
        //     exit();
        // }else{
        //     $msg = "Ocurrió un error al eliminar el pedido Nro. " . $sale_id . ".";
        //     echo $msg;
        //     exit();
        // }
        exit();
    }
    if(isset($_POST['update'])){
    //QUIEREN MODIFICAR UNA VENTA
        if(!isset($_POST['buy'])) die("error 2 controllers/viewBuys UPDATE");
        $buy = json_decode($_POST['buy']);

        if(!isset($buy->id)) die("error 3 controllers/viewBuys UPDATE");
        if(!isset($buy->pay_id)) die("error 4 controllers/viewBuys UPDATE");
        if(!isset($buy->ship_id)) die("error 5 controllers/viewBuys UPDATE");
        if(!isset($buy->description)) die("error 6 controllers/viewBuys UPDATE");

        echo $controller->updateBuy($buy);
        exit();
    }
}

if(count($_GET)>0){
    //QUIEREN CONSULTAR LAS COMPRAS O SU DETALLE
    if(!isset($_SESSION['perm'])) die ("error 7 controllers/viewBuys");
    if($_SESSION['perm'] != 1){     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
        echo "No tiene permiso para consultar los datos de las compras";
        exit();
    } 
    if(isset($_GET['getBuys'])){
        //QUIEREN CONSULTAR LAS VENTAS
        if(!isset($_GET['filterValue'])) die("error 3 controllers/viewBuys GET");
        $filterValue = $_GET['filterValue'];
        echo json_encode($controller->getBuys($filterValue));
        exit();
    }

    if(isset($_GET['getBuyDetail'])){
        //QUIEREN CONSULTAR EL DETALLE DE UNA COMPRA
        if(!isset($_GET['buy_id'])) die("error 4 controllers/viewBuys GET");
        echo json_encode($controller->getBuyItems($_GET['buy_id']));
        exit();
    }

    if(isset($_GET['getShipmentStates'])){
        //QUIEREN CONSULTAR LOS ESTADOS DE ENVIO
        //die("SHIP STATES");
        echo json_encode($controller->getShipmentStates());
        exit();
    }

    if(isset($_GET['getPaymentStates'])){
        //QUIEREN CONSULTAR LOS ESTADOS DE PAGO
        //die("PAY STATES");
        echo json_encode($controller->getPaymentStates());
        exit();
    }
}

if(!isset($_SESSION['perm'])) die ("error de permiso controllers/viewBuys");
$controller->viewCRUD($_SESSION['perm']);

?>