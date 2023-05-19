<?php 
//controllers/viewSale.php

require_once '../controllers/SaleBudgetController.php';

session_start();

if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new SaleBudgetController();

/*if(count($_POST)>0) {
    if(isset($_POST['modifyProduct'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->successMsg = $controller->modifyProduct();
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al modificar el producto: " . $e->getMessage();
            // echo json_encode($response);
            // exit;
        }
        echo json_encode($response);
        exit;
    } 
}*/

if(count($_GET)>0) {
    if(isset($_GET['viewSaleDetail'])) {
        try {
            $controller->viewSaleDetail();
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
    /*if(isset($_GET['viewProductChanges'])) {
        try {
            $controller->viewProductChanges();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->errorMsg = "Hubo un error al consultar los cambios del producto: " . $e->getMessage();
            echo json_encode($response);
            header("Location: ./viewProducts");
        }
        exit();
    }
    if(isset($_GET['viewPriceChanges'])) {
        try {
            $controller->viewPriceChanges();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->errorMsg = "Hubo un error al consultar los cambios del producto: " . $e->getMessage();
            echo json_encode($response);
            header("Location: ./viewProducts");
        }
        exit();
    }
    if(isset($_GET['viewStockChanges'])) {
        try {
            $controller->viewStockChanges();
        }
        catch (Exception $e) { 
            $response = new stdClass();
            $response->errorMsg = "Hubo un error al consultar los cambios del producto: " . $e->getMessage();
            echo json_encode($response);
            header("Location: ./viewProducts");
        }
        exit();
    }*/
}

header("Location: ./viewProducts");
exit();

?>