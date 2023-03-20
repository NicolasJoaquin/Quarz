<?php 
// controllers/newProduct.php

require_once '../controllers/ProductController.php';

session_start(); 
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new ProductController();

if(count($_POST)>0){
    if(!isset($_SESSION['perm'])) die ("error 0 controllers/newProduct");
    if($_SESSION['perm'] != 1){     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
        echo "No tiene permiso para dar de alta un producto";
        exit();
    } 
    //QUIEREN CREAR UN NUEVO PRODUCTO
    if(isset($_POST['new'])){
        if(!isset($_POST['product'])) die("error 1 controllers/newProduct");
        $product = json_decode($_POST['product']); 
        // X ACA VA LA VALIDACIÓN DE PERMISOS
        
        echo $controller->new($product); //VER SI ESTOS METODOS DE CONTROLADORES LOS PUEDO HACER POLIMORFICOS
        exit();
    }
}

if(!isset($_SESSION['perm'])) die ("error 2 controllers/newProduct");
$controller->viewForm($_SESSION['perm']);

?>