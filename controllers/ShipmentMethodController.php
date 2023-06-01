<?php
//controllers/ShipmentMethodController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/ShipmentMethods.php';
// require_once '../views/ViewShipmentStates.php';

class ShipmentMethodController extends Controller{
    public function __construct(){
        $this->models['shipmentMethods'] = new ShipmentMethods();
        // Poner títulos
        // $this->views['dashboard']       = new ViewShipmentStates(title: "Dashboard estados de envío",includeJs: "js/viewShipmentStates.js", includeCSS: "css/viewShipmentStates.css");
    }

    // Vistas
    // Getters
    public function getShipmentMethodsToSelect() {
        $shipMethods = $this->models['shipmentMethods']->getAll();
        return $shipMethods;
    }
    // public function getProductsToDashboard() {
    //     $products = $this->models['products']->getProductsListStock($_GET['filterDesc']);
    //     return $products;
    // }
    // Validadores
    // Altas y modificaciones
}

?>