<?php 
//controllers/viewBudget.php

require_once '../controllers/SaleBudgetController.php';

session_start();

if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new SaleBudgetController();

if(count($_GET)>0) {
    if(isset($_GET['viewBudgetDetail'])) {
        try {
            $controller->viewBudgetDetail();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->errorMsg = "Hubo un error al consultar la venta: " . $e->getMessage();
            exit (json_encode($response));

            echo json_encode($response);
            header("Location: ./viewSales");
        }
        exit();
    }
}

header("Location: ./viewBudgets");
exit();

?>