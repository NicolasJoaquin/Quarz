<?php 
//controllers/viewProviders.php

require_once '../controllers/ProviderController.php';

session_start();

if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new ProviderController();

if(count($_POST)>0){ 
    if(!isset($_SESSION['perm'])) die ("error 0 controllers/viewProviders");
    if($_SESSION['perm'] != 1){     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
        echo "No tiene permiso para modificar y actualizar los proveedores";
        exit();
    } 
    //Quieren modificar/borrar un proveedor
    if(isset($_POST['delete'])){
        //Quieren borrar un proveedor
        if(!isset($_POST['provider_id'])) die("error 1 controllers/viewProviders DELETE");//Valido en controlador  
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE BORRAR
        // ACA FALTA BORRAR PRIMERO LOS REGISTROS FORANEOS DE ESTE PROVEEDOR (EN ProviderController)
        echo $controller->delete($_POST['provider_id']);
        exit();
    }
    if(isset($_POST['update'])){
        //Quieren modificar un proveedor
        if(!isset($_POST['provider'])) die("error 2 controllers/viewProviders UPDATE");
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE HACER UPDATE
        $provider = json_decode($_POST['provider']); 

        echo $controller->update($provider);
        exit();
    }
}

if(count($_GET)>0){
    //QUIEREN CONSULTAR LOS PROVEEDORES
    if(!isset($_SESSION['perm'])) die ("error 3 controllers/viewProviders");
    if($_SESSION['perm'] != 1){     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
        echo "No tiene permiso para obtener datos de los proveedores";
        exit();
    } 
    if(isset($_GET['get'])){
        $filterValue = "";
        if(isset($_GET['filterValue'])) $filterValue = $_GET['filterValue'];
        echo json_encode($controller->get($filterValue));
        exit();
    }   
}

if(!isset($_SESSION['perm'])) die ("error 3 controllers/viewProviders");
$controller->viewCRUD($_SESSION['perm']);

?>