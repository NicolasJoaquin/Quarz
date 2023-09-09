<?php 
//controllers/viewClients.php

require_once '../controllers/ClientController.php';

session_start();
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new ClientController();
if(count($_GET) > 0) {
    if(isset($_GET['getToDashboard'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->data     = $controller->getClientsToDashboard();
            $response->msg = "Se consultaron con éxito los clientes."; 
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->msg = "Hubo un error al consultar los clientes: " . $e->getMessage() . " | Intentá de nuevo.";
        }
        echo json_encode($response);
        exit;
    }
    exit;
}
$controller->viewDashboard();
exit();
?>