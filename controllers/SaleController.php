<?php
//controllers/SaleController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Sales.php';
require_once '../models/Stock.php';
require_once '../models/ShipmentStates.php';
require_once '../models/PaymentStates.php';
require_once '../views/ViewSales.php';
require_once '../views/FormNewSale.php';

class SaleController extends Controller{
    public function __construct(){
        $this->models['sales'] = new Sales();
        $this->models['stock'] = new Stock();
        $this->models['shipStates'] = new ShipmentStates();
        $this->models['payStates'] = new PaymentStates();
        $this->views['form'] = new FormNewSale();
        $this->views['CRUD'] = new ViewSales();
    }

    public function registerSale($sale, $userId){ // TRAER DE LA RUTA TODA LA LOGICA PARA DESDE ACA AGREGAR LOS ITEMS TAMBIEN
        if(!($newSale = $this->models['sales']->newSale($sale, $userId))){
            return false;
        }

        $this->sendMail($newSale);
        return $newSale['sale_id'];
    }

    public function registerItems($items, $saleId){
        if(!isset($saleId)) die("error 1 controllers/SaleController registerItems");
        foreach($items as $pos => $item){
            if(!isset($item->prodId)) die("error 2 controllers/SaleController registerItems");
            if(!isset($item->prodPrice)) die("error 3 controllers/SaleController registerItems");
            if(!isset($item->prodCost)) die("error 4 controllers/SaleController registerItems");
            if(!isset($item->prodQuantity)) die("error 5 controllers/SaleController registerItems");
            $item->totalPrice = ($item->prodPrice * $item->prodQuantity);
            $item->totalCost = ($item->prodCost * $item->prodQuantity);
            $item->position = (string)$pos;
        }

        foreach($items as $pos => $item){
            if(!$this->models['stock']->discountQuantity($item)) return false;
        }

        if(!$this->models['sales']->newSaleItems($items, $saleId)) return false;

        return true;
    }

    public function getSales($filterValue){
        try{
            $ret = $this->models['sales']->getSales($filterValue);
        }
        catch(QueryErrorException $error){ //ACA HAY QUE VER COMO DEVOLVER Y MOSTRAR ESTE MSG DE ERROR (PROBABLEMENTE CONDICIONAL DESDE FRONT)
            $msg = "Se produjo un error intentando consultar el stock con el filtro " . $filterValue . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
            $ret = $msg;
        }
        return $ret;
    }

    public function getSaleItems($saleId){
        //EN ESTO FALTAN EXCEPCIONES
        return $this->models['sales']->getSaleItems($saleId);
    }

    public function getShipmentStates(){
        return $this->models['shipStates']->getAll();
    }

    public function getPaymentStates(){
        return $this->models['payStates']->getAll();
    }

    public function deleteSale($sale_id){ // MODIFICAR ESTO, PASAR LOGICA DEL MODELO ACA
        if($this->models['sales']->deleteSale($sale_id)){
            return true;
        }else{
            return false;
        }
    }

    public function updateSale($sale){
        $ret = false;
        try{
            $this->models['sales']->updateSale($sale);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando actualizar la venta " . $sale->id . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if(!$ret) $ret = "Se ha actualizado con éxito la venta " . $sale->id;
        return $ret;
    }

    // public function updateItem($item){
    //     try{
    //         $this->models['sales']->updateStock($item);
    //     }
    //     catch(QueryErrorException $error){ 
    //         $msg = "Se produjo un error intentando modificar el ítem " . $item->product_name . ",
    //         la base devuelve el siguiente error: " . $error->getErrorMsg();
    //         return false;
    //     }
    //     return true;
    // }

    public function viewCRUD(){
        $this->views['CRUD']->render();
    }

    public function viewForm(){
        $this->views['form']->render();
    }

    public function validateStock($items){
        if(empty($items)) die("error 0 controllers/SaleController validateStock");
        foreach($items as $posFLoop => $itemFLoop){
            if(!isset($itemFLoop->prodId)) die("error 1 controllers/SaleController validateStock");
            if(!isset($itemFLoop->prodQuantity)) die("error 2 controllers/SaleController validateStock");
            $prodToValidate = new StdClass();
            $prodToValidate->id = $itemFLoop->prodId;
            $prodToValidate->quantity = 0;

            foreach($items as $posSLoop => $itemSLoop){
                if($prodToValidate->id == $itemSLoop->prodId) $prodToValidate->quantity += $itemSLoop->prodQuantity;
            }

            if(!$this->models['stock']->validateStock($prodToValidate->id, strval($prodToValidate->quantity))) return false;
        }
        return true;
    }

    private function sendMail($sale){
        if(empty($_SESSION['user_email'])) return false;

        $user = $_SESSION['user'];
        $email = $_SESSION['user_email'];
        if(!empty($sale['description'])){
            $coment = "Usted ha creado la venta Nro. " . $sale['sale_id'];
        }else{
            $coment = "Usted ha creado la venta Nro. " . $sale['sale_id'] . 
            ". Notas de la venta: " . $sale['description'];
        }

        $asunto="Venta generada";
        $mensaje="Usuario: " . $user . " Mensaje: " . $coment;

        $header="From: Quarz <noreply@quarz.com>";

        $enviado = mail($email,$asunto,$mensaje,$header);

        if($enviado == true){
            return true;
        }else{
            return false;
        }
    }
}

?>