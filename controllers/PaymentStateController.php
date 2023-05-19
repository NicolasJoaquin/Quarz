<?php
//controllers/PaymentStateController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/PaymentStates.php';
// require_once '../views/ViewPaymentStates.php';

class PaymentStateController extends Controller{
    public function __construct(){
        $this->models['paymentStates'] = new PaymentStates();
        // Poner títulos
        // $this->views['dashboard']       = new ViewShipmentStates(title: "Dashboard estados de envío",includeJs: "js/viewShipmentStates.js", includeCSS: "css/viewShipmentStates.css");
    }

    // Vistas
    // Getters
    public function getPaymentStatesToSelect() {
        $payStates = $this->models['paymentStates']->getAll();
        return $payStates;
    }
    // public function getProductsToDashboard() {
    //     $products = $this->models['products']->getProductsListStock($_GET['filterDesc']);
    //     return $products;
    // }
    // Validadores
    // Altas y modificaciones
}

?>