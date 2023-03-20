<?php  
// controllers/home.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../controllers/HomeController.php'; 

session_start();

$controller = new HomeController();

if(!isset($_SESSION['log'])){    
    //NO ESTÁ LOGUEADO    
    if(count($_POST)>0){
        //SE QUIEREN LOGUEAR
        if(!isset($_POST['user'])) die ("error1 controllers/home");// valido en controlador 
        if(!isset($_POST['pass'])) die ("error2 controllers/home");

        $controller->login($_POST['user'], $_POST['pass']);
        exit();
    }
    //NO ME MANDAN NADA X POST (LO MANDO AL LOGIN)
    $controller->viewFormLogin();
    exit();
}

//EL USUARIO ESTÁ LOGUEADO (hay que mandar al home)
$controller->viewHome();

?>