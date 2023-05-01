<?php 
//controllers/viewProduct.php

require_once '../controllers/ProductController.php';

session_start();

if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new ProductController();

if(count($_POST)>0) {
    // if(!isset($_SESSION['perm'])) die ("error 0 controllers/newProduct");
    // if($_SESSION['perm'] != 1) {     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
    //     echo "No tiene permiso para dar de alta una cotización o venta";
    //     exit();
    // } 
    // // Quieren crear una nueva cotización 
    // if(isset($_POST['newBudget'])) {
    //     if(!isset($_POST['provider'])) die("error 1 controllers/newProvider");  
    //     $provider = json_decode($_POST['provider']); // ESTO SE CONVIENTE EN UN OBJETO stdClass
    //     if(!isset($provider->name)) die("error 2 controllers/newProvider");  

    //     echo $controller->new($provider); //VER SI ESTOS METODOS DE CONTROLADORES LOS PUEDO HACER POLIMORFICOS
    //     exit();
    // } 
}

if(count($_GET)>0) {
    if(isset($_GET['id'])) {
        try {
            $controller->viewProductDetail();
            exit();
        }
        catch (Exception $e) {
            $response = new stdClass();
            $response->errorMsg = "Hubo un error al consultar el producto: " . $e->getMessage();
            echo json_encode($response);
            exit(json_encode($response));
            header("Location: ./viewProducts");
            exit();
        }
    }
}

header("Location: ./viewProducts");
exit();

?>