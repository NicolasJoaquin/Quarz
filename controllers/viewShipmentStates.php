<?php 
//controllers/viewShipmentStates.php

require_once '../controllers/ShipmentStateController.php';

session_start(); 
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new ShipmentStateController();
if(count($_GET)>0) {
    if(isset($_GET['getShipmentStatesToSelect'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->shipStates = $controller->getShipmentStatesToSelect();
            $response->successMsg = "Se consultaron con éxito los estados de envío.";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar los estados de envío: " . $e->getMessage() . " | Intentá de nuevo.";
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }
}
exit;
// Fixeado de acá para arriba

?>