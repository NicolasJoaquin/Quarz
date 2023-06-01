<?php 
//controllers/viewShipmentMethods.php

require_once '../controllers/ShipmentMethodController.php';

session_start(); 
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new ShipmentMethodController();
if(count($_GET)>0) {
    if(isset($_GET['getShipmentMethodsToSelect'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->shipMethods = $controller->getShipmentMethodsToSelect();
            $response->successMsg = "Se consultaron con éxito los medios de envío.";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar los medios de envío: " . $e->getMessage() . " | Intentá de nuevo.";
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