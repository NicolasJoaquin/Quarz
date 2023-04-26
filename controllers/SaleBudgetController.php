<?php
//controllers/SaleBudgetController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Sales.php';
require_once '../models/Budgets.php';
require_once '../models/Stock.php';
require_once '../views/FormNewSaleBudget.php';
// require_once '../views/ViewBudgets.php';

class SaleBudgetController extends Controller {
    public function __construct() {
        $this->models['sales']   = new Sales();
        $this->models['budgets'] = new Budgets();
        $this->models['stock']   = new Stock();
        $this->views['form']     = new FormNewSaleBudget(title: "Nueva venta o cotización", includeJs: "./js/formSaleBudget.js", includeCSS: "./css/formSaleBudget.css");
        // $this->views['view']     = new ViewBudgets();
    }

    public function viewForm(){
        $this->views['form']->render();
    }

    public function validateExistItems($items) {
        foreach($items as $k => $item) {
            if(!isset($item->cost_price)) throw new Exception("Falta el precio de costo del ítem #$k");
            if(!isset($item->description)) throw new Exception("Falta la descripción del ítem #$k");
            if(!isset($item->product_id)) throw new Exception("Falta el identificador del ítem #$k");
            if(!isset($item->quantity)) throw new Exception("Falta la cantidad del ítem #$k");
            if(!isset($item->sale_price)) throw new Exception("Falta el precio de venta del ítem #$k");
        }
        return true;
    }

    public function validateNotEmptyItems($items) {
        foreach($items as $k => $item) {
            // if(empty($item->cost_price)) throw new Exception("Falta el precio de costo del ítem #$k");
            if(empty($item->description)) throw new Exception("Falta la descripción del ítem #$k");
            if(empty($item->product_id)) throw new Exception("Falta el identificador del ítem #$k");
            if(empty($item->quantity)) throw new Exception("Falta la cantidad del ítem #$k");
            // if(empty($item->sale_price)) throw new Exception("Falta el precio de venta del ítem #$k");
        }
        return true;
    }

    // public function validateStockOfProducts($items) {
    //     foreach($items as $item) {
    //         $prodId   = $item->product_id;
    //         $quantity = $item->quantity;
    //         $prodDesc = $item->description;
    //         foreach($items as $item2) {
    //             if($prodId == $item2->product_id)
    //                 $quantity += $item2->quantity;
    //         }
    //         if(!$this->models['stock']->validateStock($prodId, $quantity))
    //             throw new Exception("El stock del producto $prodDesc no es suficiente");
    //     }
    //     return true;
    // }

    public function validateExistBudget() {
        if(!isset($_POST['budget'])) throw new Exception("Envíe una cotización para dar de alta");
        $budget = json_decode($_POST['budget']);
        if(!isset($budget->client->client_id)) throw new Exception("Falta el cliente de la cotización");
        if(!isset($budget->items)) throw new Exception("Faltan los ítems de la cotización");
        $this->validateExistItems($budget->items);
        if(!isset($budget->notes)) throw new Exception("Faltan las notas de la cotización");
        if(!isset($budget->totalPrice)) throw new Exception("Falta el precio total de la cotización");
        return $budget;
    }

    public function validateNotEmptyBudget($budget) {
        if(empty($budget->items) || count($budget->items) == 0) throw new Exception("Envíe ítems para dar de alta en la cotización");
        $this->validateNotEmptyItems($budget->items);
        if(empty($budget->client->client_id)) throw new Exception("Envíe el identificador del cliente para dar de alta la cotización");
        if(empty($budget->totalPrice) && $budget->totalPrice != 0) throw new Exception("Envíe el precio total de la cotización");
        return true;
    }

    public function validateExistSale() {
        if(!isset($_POST['sale'])) throw new Exception("Envíe una venta para dar de alta");
        $sale = json_decode($_POST['sale']);
        if(!isset($sale->client->client_id)) throw new Exception("Falta el cliente de la venta");
        if(!isset($sale->items)) throw new Exception("Faltan los ítems de la venta");
        $this->validateExistItems($sale->items);
        if(!isset($sale->notes)) throw new Exception("Faltan las notas de la venta");
        if(!isset($sale->totalPrice)) throw new Exception("Falta el precio total de la venta");
        return $sale;
    }

    public function validateNotEmptySale($sale) {
        if(empty($sale->items) || count($sale->items) == 0) throw new Exception("Envíe ítems para dar de alta en la venta");
        $this->validateNotEmptyItems($sale->items);
        if(empty($sale->client->client_id)) throw new Exception("Envíe el identificador del cliente para dar de alta la venta");
        return true;
    }

    public function newBudget() {
        $budget = $this->validateExistBudget();
        $this->validateNotEmptyBudget($budget);
        $budget->id = $this->models['budgets']->newBudget($budget);
        $this->models['budgets']->newBudgetItems($budget->items, $budget->id);
        $msg = "Se dió de alta la cotización #$budget->id";
        return $msg;
    }

    public function newSale() {
        $sale = $this->validateExistSale();
        $this->validateNotEmptySale($sale);
        $this->models['stock']->discountValidatedQuantities($sale->items); // Acá arroja excepciones si no hay stock, la venta no se genera
        $sale->id = $this->models['sales']->newSale($sale);
        $this->models['sales']->newSaleItems($sale->items, $sale->id);
        $msg = "Se dió de alta la venta #$sale->id";
        return $msg;
    }










    public function registerSale($sale, $userId) { 
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