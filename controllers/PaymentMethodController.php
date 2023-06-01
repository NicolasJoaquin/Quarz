<?php
//controllers/PaymentMethodController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/PaymentMethods.php';
// require_once '../views/ViewShipmentStates.php';

class PaymentMethodController extends Controller{
    public function __construct(){
        $this->models['paymentMethods'] = new PaymentMethods();
        // Poner títulos
        // $this->views['dashboard']       = new ViewShipmentStates(title: "Dashboard estados de envío",includeJs: "js/viewShipmentStates.js", includeCSS: "css/viewShipmentStates.css");
    }

    // Vistas
    // Getters
    public function getPaymentMethodsToSelect() {
        $payMethods = $this->models['paymentMethods']->getAll();
        return $payMethods;
    }
    // public function getProductsToDashboard() {
    //     $products = $this->models['products']->getProductsListStock($_GET['filterDesc']);
    //     return $products;
    // }
    // Validadores
    // Altas y modificaciones
}

?>