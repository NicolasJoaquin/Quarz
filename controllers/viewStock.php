<?php 
//controllers/viewStock.php

require_once '../controllers/StockController.php';

session_start();

if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new StockController();

if(count($_POST)>0){
    //Quieren modificar el stock
    if(isset($_POST['update'])){
        //Quieren modificar un item
        if(!isset($_POST['item'])) die("error 1 controllers/viewStock UPDATE");
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE HACER UPDATE
        $item = json_decode($_POST['item']); 

        if($controller->update($item)){ //MODIFICAR ESTO
            $msg = "Se actualizó con éxito el ítem";
            echo $msg;
            exit();
        }else{
            $msg = "Ocurrió un error al actualizar el ítem";
            echo $msg;
            exit();
        }
    }
}

if(count($_GET)>0){
    //QUIEREN CONSULTAR LOS ITEMS DEL STOCK
    if(isset($_GET['get'])){
        $filterValue = "";
        if(isset($_GET['filterValue'])) $filterValue = $_GET['filterValue'];
        
        echo json_encode($controller->get($filterValue));
        exit();
    }   
}

$controller->viewCRUD();

?>