<?php 
//controllers/newSale.php

require_once '../controllers/SaleController.php';

session_start(); 
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new SaleController();

if(count($_POST)>0){
    if(isset($_POST['new'])){
        //QUIEREN CREAR UNA NUEVA VENTA
        // X ACA VA LA VALIDACIÓN DE PERMISOS
        if(!isset($_POST['sale'])) die("error 1 controllers/newSale NEw");
        $sale = json_decode($_POST['sale']); 
        if(!isset($sale->items)) die("error 2 controllers/newSale NEw");

        if(!$controller->validateStock($sale->items)) die("error 3 controllers/newSale NEw (CONTROL DE STOCK)");
        
        $userId = $_SESSION['user_id']; // ESTO LO PUEDO AGREGAR A LA VENTA
        if(!$saleId = $controller->registerSale($sale, $userId)) die("error 4 controllers/newSale NEW");

        if($controller->registerItems($sale->items, $saleId)){
            echo "Se ha dado de alta exitosamente el pedido";
            exit();
        }else{
            echo "Ocurrió un error al dar de alta el pedido";
            exit();
        }
    }
}  

$controller->viewForm();






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