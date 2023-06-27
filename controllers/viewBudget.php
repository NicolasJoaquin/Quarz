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
        exit;
    }

    if(isset($_GET['newBudgetVersion'])) {
        try {
            $controller->viewFormNewBudgetVersion();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->errorMsg = "Hubo un error, intentá nuevamente: " . $e->getMessage();
            exit (json_encode($response));

            echo json_encode($response);
            header("Location: ./viewBudgets"); // Revisar redirección
        }
        exit;
    }

    if(isset($_GET['getBudgetToNewVersion'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->budget       = $controller->getBudgetToNewVersion();
            $response->successMsg   = "Se consultó con éxito la cotización #" . sprintf("%'.04d\n", $response->budget->info['budget_number']); 
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar la cotización: " . $e->getMessage() . " | Intentá de nuevo.";
        }
        echo json_encode($response);
        exit;
    }
}

header("Location: ./viewBudgets");
exit();

?>