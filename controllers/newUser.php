<?php 
//controllers/newUser.php

require_once '../controllers/NewUserController.php';

session_start();

if(isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new NewUserController();

if(count($_POST)>0){
    //QUIEREN CREAR UN NUEVO USUARIO
    if(!isset($_POST['userData'])) die("error 1 controllers/newUser");
    $user = json_decode($_POST['userData']); // ESTO SE CONVIENTE EN UN OBJETO stdClass
    if(strcmp($user->pass, $user->pass_validation) != 0) die ("error 2 controllers/newUser");
    //REVISAR SI NO TENGO QUE AGREGAR PERMID
    if($controller->register($user)){
        echo "Se ha dado de alta exitosamente al usuario " . $user->user;
        exit();
    }else{
        echo "Ocurrió un error al dar de alta al usuario " . $user->user;
        exit();
    }
}

$controller->viewForm();
?>