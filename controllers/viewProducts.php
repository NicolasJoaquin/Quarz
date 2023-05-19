<?php 
//controllers/viewProducts.php

require_once '../controllers/ProductController.php';

session_start();
if(!isset($_SESSION['log'])) {
    header("Location: ./home");
    exit();
}

$controller = new ProductController();
if(count($_GET)>0) {
    if(isset($_GET['getProductsToDashboard'])) {
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->products   = $controller->getProductsToDashboard();
            $response->successMsg = "Se consultaron con éxito los productos";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar los productos: " . $e->getMessage() . " | Intentá de nuevo.";
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }
    if(isset($_GET['getProductsToSelect'])) { // Falta fix, esto lo consume el formulario de ventas y cotizaciones (por ahora)
        $response = new stdClass();
        $response->state = 1;
        try {
            $response->products   = $controller->get(""); // Falta fix
            $response->successMsg = "Se consultaron con éxito los productos";
        }
        catch (Exception $e) {
            $response->state = 0;
            $response->errorMsg = "Hubo un error al consultar los productos: " . $e->getMessage() . " | Intentá de nuevo.";
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }
}
$controller->viewDashboard();


if(count($_POST)>0) {
    // if(!isset($_SESSION['perm'])) die ("error 0 controllers/newProduct");
    // if($_SESSION['perm'] != 1) {     // MODIFICAR ESTO PARA QUE EL CONTROL SE HAGA EN CONTROLADOR Y NO EN LA RUTA, YO MANDO EL PERMISO Y EL CONTROLLER ME DICE SI PUEDO O NO HACER LO QUE QUIERO
    //     echo "No tiene permiso para dar de alta una cotización o venta";
    //     exit();
    // } 
    //Quieren modificar/borrar un producto
    if(isset($_POST['delete'])) { // MODIFICAR ACA PARA QUE ME ELIMINE LOS DATOS FORANEOS
        //Quieren borrar un producto
        if(!isset($_POST['product_id'])) die("error 1 controllers/viewProducts DELETE");//Valido en controlador  
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE BORRAR
        $id = $_POST['product_id'];
        echo $controller->delete($id);
        exit();
    }

    if(isset($_POST['update'])) {
        //Quieren modificar un producto
        if(!isset($_POST['product'])) die("error 2 controllers/viewProducts UPDATE");
        // ACA FALTA HACER LA VERIFICACIÓN DE PERMISOS ANTES DE HACER UPDATE
        $product = json_decode($_POST['product']); // ESTO SE CONVIENTE EN UN OBJETO stdClass


        //toy aca, PENDIENTE DE MODIFICAR

        if($controller->update($product)){
            $msg = "Se actualizó con éxito el producto con código " . $product->id . ".";
            echo $msg;
            exit();
        }else{
            $msg = "Ocurrió un error al actualizar el producto con código " . $product->id . ".";
            echo $msg;
            exit();
        }
    }
}

?>