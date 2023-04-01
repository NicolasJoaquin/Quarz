<?php 
//controllers/newClient.php

require_once '../controllers/NewClientController.php';

session_start();

if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new NewClientController();

if(count($_POST)>0){ //FALTA MODIFICAR TODO ESTO
    //QUIEREN CREAR UN NUEVO CLIENTE --> paso a variables limpias
    $id = false; 
    $name = $_POST['name'];
    $CUIT = $_POST['CUIT'];
    $nickname = $_POST['nickname'];
    $direction = $_POST['direction'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if(!isset($name)) die ("error1 controllers/newClient");// valido seteos en controlador 
    if(!isset($CUIT)) die ("error2 controllers/newClient");
    if(!isset($nickname)) die ("error3 controllers/newClient");
    if(!isset($direction)) die ("error4 controllers/newClient");
    if(!isset($email)) die ("error5 controllers/newClient");
    if(!isset($phone)) die ("error6 controllers/newClient");

   // $clientData = array('id'=>$id, 'name'=>$name, 'CUIT'=>$CUIT, 'nickname'=>$nickname, 'direction'=>$direction, 'email'=>$email, 'phone'=>$phone);

    $client = new stdClass;
    $client->id = $id;
    $client->name = $name;
    $client->CUIT = $CUIT;
    $client->nickname = $nickname;
    $client->direction = $direction;
    $client->email = $email;
    $client->phone = $phone;

    $controller->register($client);
    exit();
}

$controller->viewForm();
?>