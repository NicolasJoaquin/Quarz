<?php 
//controllers/newSaleBudget.php

require_once '../controllers/SaleBudgetController.php';

session_start();

if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new SaleBudgetController();

if(count($_POST)>0) {
    // if(!isset($_SESSION['perm'])) die ("error 0 controllers/newProduct");
    // if($_SESSION['perm'] != 1) {     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
    //     echo "No tiene permiso para dar de alta una cotización o venta";
    //     exit();
    // } 
    if(isset($_POST['newBudget'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->successMsg = $controller->newBudget();
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al dar de alta el presupuesto: " . $e->getMessage();
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    } 
    if(isset($_POST['newSale'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->successMsg = $controller->newSale();
        }catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al dar de alta la venta: " . $e->getMessage();
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    } 
}
$controller->viewForm();
exit();
?>