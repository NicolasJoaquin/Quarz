<?php
//controllers/ShipmentStateController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/ShipmentStates.php';
// require_once '../views/ViewShipmentStates.php';

class ShipmentStateController extends Controller{
    public function __construct(){
        $this->models['shipmentStates'] = new ShipmentStates();
        // Poner títulos
        // $this->views['dashboard']       = new ViewShipmentStates(title: "Dashboard estados de envío",includeJs: "js/viewShipmentStates.js", includeCSS: "css/viewShipmentStates.css");
    }

    // Vistas
    // Getters
    public function getShipmentStatesToSelect() {
        $shipStates = $this->models['shipmentStates']->getAll();
        return $shipStates;
    }
    // public function getProductsToDashboard() {
    //     $products = $this->models['products']->getProductsListStock($_GET['filterDesc']);
    //     return $products;
    // }
    // Validadores
    // Altas y modificaciones
}

?>