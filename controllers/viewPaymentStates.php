<?php 
//controllers/viewPaymentStates.php

require_once '../controllers/PaymentStateController.php';

session_start(); 
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new PaymentStateController();
if(count($_GET)>0) {
    if(isset($_GET['getPaymentStatesToSelect'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->payStates = $controller->getPaymentStatesToSelect();
            $response->successMsg = "Se consultaron con éxito los estados de pago.";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar los estados de pago: " . $e->getMessage() . " | Intentá de nuevo.";
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