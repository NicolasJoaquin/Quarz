<?php 
//controllers/newClient.php

require_once '../controllers/ClientController.php';

session_start();
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new ClientController();

if(count($_POST) > 0) {
    if(isset($_POST['newClient'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $controller->newClient();
            $response->msg = "El cliente se dió de alta correctamente";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->msg = "Hubo un error al dar de alta el cliente: " . $e->getMessage();
        }
        echo json_encode($response);
        exit;
    }
    exit();
}

$controller->viewForm();
exit();
?>