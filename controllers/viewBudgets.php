<?php 
//controllers/viewBudgets.php

require_once '../controllers/SaleBudgetController.php';

session_start(); 
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new SaleBudgetController();
if(count($_GET)>0) {
    if(isset($_GET['getBudgetsToDashboard'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->budgets    = $controller->getBudgetsToDashboard();
            $response->successMsg = "Se consultaron con éxito las cotizaciones."; 
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar las cotizaciones: " . $e->getMessage() . " | Intentá de nuevo.";
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }
}
$controller->viewBudgetDashboard();
exit;

// Fixeado de acá para arriba



?>