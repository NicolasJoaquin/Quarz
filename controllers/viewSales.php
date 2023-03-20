<?php 
//controllers/viewSales.php

require_once '../controllers/SaleController.php';

session_start(); 
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new SaleController();

if(count($_POST)>0){
    if(isset($_POST['delete'])){
        //QUIEREN BORRAR UNA VENTA
        if(!isset($_POST['sale_id'])) die("error 1 controllers/viewSales DELETE");
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE HACER UPDATE
        $sale_id = $_POST['sale_id']; 

        if($controller->deleteSale($sale_id)){ //MODIFICAR ACA PARA QUE PASE EL MESAJE DE ERROR DEL CONTROL DE EXCEPCIONES
            $msg = "Se eliminó con éxito el pedido Nro. " . $sale_id . ".";
            echo $msg;
            exit();
        }else{
            $msg = "Ocurrió un error al eliminar el pedido Nro. " . $sale_id . ".";
            echo $msg;
            exit();
        }
    }
    if(isset($_POST['update'])){
    //QUIEREN MODIFICAR UNA VENTA
        if(!isset($_POST['sale'])) die("error 2 controllers/viewSales UPDATE");
        $sale = json_decode($_POST['sale']);

        if(!isset($sale->id)) die("error 3 controllers/viewSales UPDATE");
        if(!isset($sale->pay_id)) die("error 4 controllers/viewSales UPDATE");
        if(!isset($sale->ship_id)) die("error 5 controllers/viewSales UPDATE");
        if(!isset($sale->description)) die("error 6 controllers/viewSales UPDATE");

        echo $controller->updateSale($sale);
        exit();
    }
}

if(count($_GET)>0){
    //QUIEREN CONSULTAR LAS VENTAS O SU DETALLE
    if(isset($_GET['getSales'])){
        //QUIEREN CONSULTAR LAS VENTAS
        if(!isset($_GET['filterValue'])) die("error 3 controllers/viewSales GET");
        $filterValue = $_GET['filterValue'];
        echo json_encode($controller->getSales($filterValue));
        exit();
    }

    if(isset($_GET['getSaleDetail'])){
        //QUIEREN CONSULTAR EL DETALLE DE UNA VENTA
        if(!isset($_GET['sale_id'])) die("error 4 controllers/viewSales GET");
        echo json_encode($controller->getSaleItems($_GET['sale_id']));
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

$controller->viewCRUD();

?>