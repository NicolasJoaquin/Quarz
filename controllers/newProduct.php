<?php 
// controllers/newProduct.php

require_once '../controllers/ProductController.php';

session_start(); 
if(!isset($_SESSION['log'])){
    header("Location: ./home");
    exit();
}

$controller = new ProductController();

if(count($_POST)>0){
    if(isset($_POST['new'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->successMsg = $controller->newProduct();
        }catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al dar de alta el producto: " . $e->getMessage();
        }
        echo json_encode($response);
        exit;
    } 
}

$controller->viewForm();
exit();

?>