<?php
//controllers/BuyController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Buys.php';
require_once '../models/Stock.php';
require_once '../models/ShipmentStates.php';
require_once '../models/PaymentStates.php';
require_once '../views/ViewBuys.php';
require_once '../views/FormNewBuy.php';

class BuyController extends Controller{
    public function __construct(){
        $this->models['buys'] = new Buys();
        $this->models['stock'] = new Stock();
        $this->models['shipStates'] = new ShipmentStates();
        $this->models['payStates'] = new PaymentStates();
        $this->views['CRUD'] = new ViewBuys();
        $this->views['form'] = new FormNewBuy("Nueva compra");
    } 

    public function registerBuy($buy){ // TRAER DE LA RUTA TODA LA LOGICA PARA DESDE ACA AGREGAR LOS ITEMS TAMBIEN
        $ret = "Se ha dado de alta la compra correctamente";
        try{
            $newBuy = $this->models['buys']->newBuy($buy);
            if(!$this->registerItems($buy->items, $newBuy['buy_id'])) $ret = "Hubo un problema al dar de alta los ítems de la compra";
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando dar de alta la compra, la base devuelve el siguiente error: " . $error->getErrorMsg();
        }

        //$this->sendMail($newBuy);
        return $ret;
    }

    private function registerItems($items, $buyId){
        if(empty($buyId)) die("error 1 controllers/BuyController registerItems");
        foreach($items as $pos => $item){
            if(!isset($item->product_id)) die("error 2 controllers/SaleController registerItems");
            if(!isset($item->cost_price)) die("error 4 controllers/SaleController registerItems");
            if(!isset($item->quantity)) die("error 5 controllers/SaleController registerItems");
            //$item->total_cost = ($item->cost_price * $item->quantity); // NO IRÍA, LO CALCULA LA BD
            $item->position = (string)$pos;
        }

        if(!$this->models['buys']->validateItems($items, $buyId)){
            $this->models['buys']->deleteBuy($buyId);
            return false;
        } 
        $this->models['buys']->newBuyItems($items, $buyId); // si devuelve excepción la trata registerBuy() (CAMBIAR EN SaleController)

        return true;
    }
    // ESTOY ACA
    public function getBuys($filterValue){
        try{
            $ret = $this->models['buys']->getBuys($filterValue);
        }
        catch(QueryErrorException $error){ //ACA HAY QUE VER COMO DEVOLVER Y MOSTRAR ESTE MSG DE ERROR (PROBABLEMENTE CONDICIONAL DESDE FRONT)
            $ret = "Se produjo un error intentando consultar el stock con el filtro " . $filterValue . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        return $ret;
    }

    public function getBuyItems($buyId){
        //EN ESTO FALTAN EXCEPCIONES
        return $this->models['buys']->getBuyItems($buyId);
    }

    public function getShipmentStates(){
        return $this->models['shipStates']->getAll();
    }

    public function getPaymentStates(){
        return $this->models['payStates']->getAll();
    }

    public function deleteBuy($buy_id){ // MODIFICAR ESTO, PASAR LOGICA DEL MODELO ACA
        $ret = "Se ha eliminado con éxito la compra Nro. " . $buy_id;
        try{
            $this->models['buys']->deleteBuy($buy_id);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando borrar la compra " . $buy_id . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }

        return $ret;
    }

    public function updateBuy($buy){
        $ret = "Se ha actualizado con éxito la compra " . $buy->id;
        try{
            //ESTOY ACA
            $oldBuy = $this->models['buys']->getBuy($buy->id);
            if($oldBuy['ship_id'] != $buy->ship_id && $buy->ship_id == 4){ //MODIFICAR ESTO
                $items = $this->models['buys']->getBuyItems($buy->id);
                foreach($items as $pos => $item){
                    $this->models['stock']->addQuantity($item);
                }
                $ret .= ", se dieron de alta sus ítems en el stock";
            }
            
            $this->models['buys']->updateBuy($buy);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando actualizar la compra " . $buy->id . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
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

    public function viewCRUD($perm){
        if($perm == 1){
            $this->views['CRUD']->render();
        }else {
            echo "No tiene acceso a la visualización y modificación de compras";
            exit();
        }
    }

    public function viewForm($perm){
        if($perm == 1){
            $this->views['form']->render();
        }else {
            echo "No tiene acceso al alta de compras";
            exit();
        }
    }

    private function sendMail($sale){ //A MODIFICAR ACA EN COMPRAS
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