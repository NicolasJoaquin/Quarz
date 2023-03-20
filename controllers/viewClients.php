<?php 
//controllers/viewClients.php

require_once '../controllers/ClientController.php';

session_start();

if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new ClientController();

if(count($_POST)>0){
    //Quieren modificar/borrar un cliente
    if(isset($_POST['delete'])){
        //Quieren borrar un cliente
        if(!isset($_POST['client_id'])) die("error 1 controllers/viewClients DELETE");//Valido en controlador  
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE BORRAR

        echo $controller->delete($_POST['client_id']);
        exit();
    }

    if(isset($_POST['update'])){
        //Quieren modificar un cliente
        if(!isset($_POST['client'])) die("error 2 controllers/viewClients UPDATE");
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE HACER UPDATE
        $client = json_decode($_POST['client']); 

        echo $controller->update($client);
        exit();
    }
}

if(count($_GET)>0){
    //QUIEREN CONSULTAR LOS CLIENTES
    if(isset($_GET['get'])){
        $filterValue = "";
        if(isset($_GET['filterValue'])) $filterValue = $_GET['filterValue'];
        echo json_encode($controller->get($filterValue));
        exit();
    }
}
$controller->viewCRUD();

?>