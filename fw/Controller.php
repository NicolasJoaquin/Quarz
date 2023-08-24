<?php
//fw/Controller.php

abstract class Controller{
    protected $models;
    protected $views;

    public function __construct(){
        
    }

    // public function dateToNormal($date) { // Pendiente de desarrollo

    // }

    /* Acá o en el pasaje de objeto a array se podría usar Reflection para recorrer las propiedades y transformarlas */
    /* Acá se puede implementar tipado estricto para implementar una interfaz iItem */
    public function arrayItemToObject($item) { 
        $objectItem = new stdClass; 
        $objectItem->product_id     = (isset($item['product_id'])) ? $item['product_id'] : false;
        $objectItem->sale_price     = (isset($item['sale_price'])) ? $item['sale_price'] : false;
        $objectItem->cost_price     = (isset($item['cost_price'])) ? $item['cost_price'] : false;
        $objectItem->quantity       = (isset($item['quantity'])) ? $item['quantity'] : false;
        $objectItem->total_price    = (isset($item['total_price'])) ? $item['total_price'] : false;
        $objectItem->total_cost     = (isset($item['total_cost'])) ? $item['total_cost'] : false;
        $objectItem->position       = (isset($item['position'])) ? $item['position'] : false;
        return $objectItem;
    }
    /* Acá se puede implementar tipado estricto para implementar una interfaz iItem */
    public function arrayItemsToObject($items) { 
        $objectItems = array();
        foreach($items as $item) 
            $objectItems[] = $this->arrayItemToObject($item);
        return $objectItems;
    }

    public function sqlDateToNormal($sqlDate) {
        $ano = substr($sqlDate, 0, 4);
        $mes = substr($sqlDate, 5, 2);
        $dia = substr($sqlDate, 8, 2);
        $newDate = $dia . "/" . $mes . "/" . $ano;
        return $newDate;
    }
}
    
?>