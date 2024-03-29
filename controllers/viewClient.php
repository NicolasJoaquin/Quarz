<?php 
//controllers/viewClient.php

require_once '../controllers/ClientController.php';

session_start();

if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new ClientController();

if(count($_GET) > 0) {
    if(isset($_GET['viewClientDetail'])) {
        try {
            $controller->viewClientDetail();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->msg = "Hubo un error al consultar el cliente: " . $e->getMessage();
            echo json_encode($response);
            header("Location: ./viewClients");
        }
        exit();
    }
    if(isset($_GET['viewClientSales'])) {
        try {
            $controller->viewClientSales();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->msg = "Hubo un error al consultar las ventas del cliente: " . $e->getMessage();
            echo json_encode($response);
            header("Location: ./viewClients");
        }
        exit();
    }
    if(isset($_GET['viewClientBudgets'])) {
        try {
            $controller->viewClientBudgets();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->msg = "Hubo un error al consultar las cotizaciones del cliente: " . $e->getMessage();
            echo json_encode($response);
            header("Location: ./viewClients");
        }
        exit();
    }
    exit();
}
if(count($_POST)>0) {
    if(isset($_POST['editClient'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $controller->editClient();
            $response->msg = "Se modificó el cliente correctamente";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->msg   = "Hubo un error al modificar el cliente: " . $e->getMessage();
        }
        echo json_encode($response);
        exit;
    }
    exit;
}

header("Location: ./viewClients");
exit();

?>