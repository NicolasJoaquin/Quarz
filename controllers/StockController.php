<?php
//controllers/StockController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Stock.php';
require_once '../views/ViewStock.php';

class StockController extends Controller{
    public function __construct(){
        $this->models['stock'] = new Stock();
        $this->views['CRUD'] = new ViewStock();
    }

    public function update($item){ // MODIFICAR Y CORREGIR ESTE METODO
        try{
            $this->models['stock']->updateStock($item);
        }
        catch(QueryErrorException $error){ 
            $msg = "Se produjo un error intentando modificar el ítem " . $item->product_name . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
            return false;
        }
        return true;
    }

    public function get($filterValue){
        try{
            $ret = $this->models['stock']->getStock($filterValue);
        }
        catch(QueryErrorException $error){ //MODIFICAR ESTE CONTROL DE EXCEPCIONES
            $ret = "Se produjo un error intentando consultar el stock con el filtro " . $filterValue . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        return $ret;
    }

    public function viewCRUD(){
        $this->views['CRUD']->render();
    }
}

?>