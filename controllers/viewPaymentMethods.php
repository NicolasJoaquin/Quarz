<?php 
//controllers/viewPaymentMethods.php

require_once '../controllers/PaymentMethodController.php';

session_start(); 
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new PaymentMethodController();
if(count($_GET)>0) {
    if(isset($_GET['getPaymentMethodsToSelect'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->payMethods = $controller->getPaymentMethodsToSelect();
            $response->msg = "Se consultaron con éxito los medios de pago.";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->msg = "Hubo un error al consultar los medios de pago: " . $e->getMessage() . " | Intentá de nuevo.";
        }
        echo json_encode($response);
        exit;
    }
    exit;
}
exit;

?>