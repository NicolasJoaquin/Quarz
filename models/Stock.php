<?php
//models/Stock.php

require_once '../fw/fw.php';

class Stock extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM view_stock");
        return $this->db->fetchAll();
    }

    public function getStock($filterValue){
        // VALIDO FILTRO
        if(!empty($filterValue)){
            $filterValue = substr($filterValue, 0, 100);
            $filterValue = $this->db->escape($filterValue);
            $filterValue = $this->db->escapeWildcards($filterValue);
        }

        $this->db->query("SELECT *
                            FROM view_stock as v 
                            WHERE v.stock_id LIKE '%$filterValue%' OR 
                                    v.product_id LIKE '%$filterValue%' OR
                                    v.product_name LIKE '%$filterValue%' OR
                                    v.warehouse_name LIKE '%$filterValue%' OR
                                    v.quantity LIKE '%$filterValue%'"); // MODIFICAR LIMIT
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function updateStock($item){ //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO // FALTA
        //Valido id
        if(!ctype_digit($item->id)) die ("error 0 updateStock/Stock (modelo)");

        //Valido quantity
        if(!ctype_digit($item->quantity)) die ("error 1 updateStock/Stock (modelo)");

         //QUERY UPDATE
         $this->db->query("UPDATE stock_items
                            SET quantity = '$item->quantity'
                            WHERE stock_item_id = $item->id");
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }





    public function discountQuantity($item) { //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO // OK
        $this->validateItem($item);

        //QUERY UPDATE Y VERIFICACIÓN
        $updateQuery = "UPDATE stock_items AS s SET s.quantity = s.quantity - $item->quantity WHERE product_id = $item->product_id";
        $this->db->query($updateQuery);
        $this->db->validateLastQuery();
        return true;
    }

    public function discountQuantities($items) { // OK
        foreach($items as $item) {
            $this->discountQuantity($item);
        }
        return true;
    }

    public function discountValidatedQuantity($item) { // OK
        $this->validateItem($item); // Ya se valida en discountQuantity()

        if(!$this->validateStockItem($item->product_id, $item->quantity))
            throw new Exception("El stock del producto #$item->product_id no es suficiente");

        $this->discountQuantity($item);
        return true;
    }

    public function discountValidatedQuantities($items) { // OK
        $this->validateItems($items); // Ya se valida en discountQuantitites()

        $this->validateStockItems($items);
    
        $this->discountQuantities($items);

        return true;
    }

    //VERIFICADORES------------------------------------------------------------------------------------------------------------------
    private function validateStockItem($prodId, $quantity) { // OK // Va en private porque hay select sin validación directa
        // $this->db->validateSanitizeId($prodId, "El identificador del producto es inválido");
        // $this->db->validateSanitizeFloat($quantity, "La cantidad del producto es inválida");
        // REVISAR
        if($quantity <= 0)  
            throw new Exception("La cantidad del producto #$prodId no puede ser menor o igual a 0, no se puede descontar del stock");

        $query = "SELECT product_id, SUM(quantity) AS total_quantity 
                    FROM `stock_items` WHERE product_id = $prodId 
                    GROUP BY product_id";
        $this->db->query($query);
        $this->db->validateLastQuery();
        $stockQuantity = $this->db->fetch()['total_quantity'];
        if($stockQuantity < $quantity) return false;
        return true;
    }

    private function validateStockItems($items) {  //OK // Va en private porque llama a validateStockItem() sin validación directa
        // $this->validateItems($items);
        foreach($items as $item) {
            $quantity = 0;
            $prodId   = $item->product_id;
            $prodDesc = $item->description;    
            foreach($items as $item2) {
                if($prodId == $item2->product_id) 
                    $quantity += $item2->quantity;
            }
            if(!$this->validateStockItem($prodId, $quantity))
                throw new Exception("El stock del producto #$prodId- $prodDesc no es suficiente.");
        }
        return true;
    }

    public function validateItem(&$item) { // OK
        $item->product_id = (int)trim($item->product_id);
        $this->db->validateSanitizeId($item->product_id, "El identificador del producto #$item->product_id es inválido");

        $item->quantity = (float)trim($item->quantity);
        $this->db->validateSanitizeFloat($item->quantity, "La cantidad del producto #$item->product_id es inválida");

        return true;
    }

    public function validateItems(&$items) { // OK
        foreach($items as $item) {
            if(!$this->validateItem($item))
                return false;
        }
        return true;
    }










    public function addQuantity($item){ //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO
        //Valido product_id
        if(!ctype_digit($item['product_id'])) die ("error 0 addQuantity/Stock (modelo)");

        //Valido quantity
        if(!ctype_digit($item['quantity'])) die ("error 1 addQuantity/Stock (modelo)");

        
        $product_id = $item['product_id']; //REVISAR ESTOOOOOOOOOOO
        $quantity = $item['quantity'];

        //QUERY SELECT Y VERIFICACIÓN
        $selectQuery = "SELECT * FROM stock_items WHERE product_id = '$product_id'";
        $this->db->query($selectQuery);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  

        if(!$stockItem = $this->db->fetch()) die("error 2 addQuantity/Stock (modelo)"); 
        $newQuantity = $stockItem['quantity'] + $quantity;
        
        //QUERY UPDATE Y VERIFICACIÓN
        $updateQuery = "UPDATE stock_items SET quantity = '$newQuantity' WHERE product_id = $product_id";
        $this->db->query($updateQuery);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }


}

?>