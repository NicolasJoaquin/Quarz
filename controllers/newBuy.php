<?php 
//controllers/newBuy.php

require_once '../controllers/BuyController.php';

session_start(); 
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new BuyController();

if(count($_POST)>0){
    if(!isset($_SESSION['perm'])) die ("error 0 controllers/newBuy");
    if($_SESSION['perm'] != 1){     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
        echo "No tiene permiso para dar de alta una compra";
        exit();
    } 
    if(isset($_POST['new'])){
        //QUIEREN CREAR UNA NUEVA COMPRA
        if(!isset($_POST['buy'])) die("error 1 controllers/newBuy NEW");
        $buy = json_decode($_POST['buy']); 
        if(!isset($buy->items)) die("error 2 controllers/newBuy NEW");
        if(!isset($buy->provider_id)) die("error 3 controllers/newBuy NEW");
        if(!isset($buy->total)) die("error 4 controllers/newBuy NEW");
        if(!isset($buy->description)) die("error 5 controllers/newBuy NEW");
        if(!isset($_SESSION['user_id'])) die("error 6 controllers/newBuy NEW");

        $buy->user_id = $_SESSION['user_id'];
        echo $controller->registerBuy($buy); //aca
        exit();
    }
}  

if(!isset($_SESSION['perm'])) die ("error 5 controllers/newBuy");
$controller->viewForm($_SESSION['perm']);






// if(isset($_POST['update'])){
//     //QUIEREN MODIFICAR UNA VENTA
//     if(!isset($_POST['saleData'])) die("error 2 controllers/newSale UPDATE");
//     // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE HACER UPDATE
//     $sale = json_decode($_POST['saleData']); // ESTO SE CONVIENTE EN UN OBJETO stdClass

//     if($controller->update($sale)){
//         $msg = "Se actualizó con éxito el pedido con id " . $sale->id . ".";
//         echo $msg;
//         exit();
//     }else{
//         $msg = "Ocurrió un error al actualizar el pedido con id " . $sale->id . ".";
//         echo $msg;
//         exit();
//     }
// }


// if(count($_GET)>0){
//     //QUIEREN CONSULTAR LAS VENTAS
//     if(!isset($_GET['filterValue'])) die("error 3 controllers/newSale GET");
//     $filterValue = $_GET['filterValue'];
//     echo json_encode($controller->get($filterValue));
//     exit();
// }


?>