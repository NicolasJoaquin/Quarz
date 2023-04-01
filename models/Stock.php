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
    public function updateStock($item){ //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO
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

    public function discountQuantity($item){ //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO, modificar los nombres de los atributos
        //Valido id
        if(!ctype_digit($item->prodId)) die ("error 0 discountQuantity/Stock (modelo)");

        //Valido quantity
        if(!ctype_digit($item->prodQuantity)) die ("error 1 discountQuantity/Stock (modelo)");

        //QUERY SELECT Y VERIFICACIÓN
        $selectQuery = "SELECT * FROM stock_items WHERE product_id = '$item->prodId'";
        $this->db->query($selectQuery);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  

        if(!$stockItem = $this->db->fetch()) die("error 2 discountQuantity/Stock (modelo)"); //estoy aca
        $newQuantity = $stockItem['quantity'] - $item->prodQuantity;
        
        //QUERY UPDATE Y VERIFICACIÓN
        $updateQuery = "UPDATE stock_items SET quantity = '$newQuantity' WHERE product_id = $item->prodId";
        $this->db->query($updateQuery);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
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

    //VERIFICADORES------------------------------------------------------------------------------------------------------------------
    public function validateStock($prodId, $prodQuantity){
        //Valido prodId
        if(!ctype_digit($prodId)) die ("error 1 validateStock/Stock (modelo)");
        //Valido prodQuantity
        if(!ctype_digit($prodQuantity)) die ("error 2 validateStock/Stock (modelo)");
        
        $query = "SELECT product_id, SUM(quantity) as total_quantity FROM `stock_items` GROUP BY product_id";
        $this->db->query($query);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError()); 

        $items = $this->db->fetchAll();
        foreach($items as $pos => $item){
            if($item['product_id'] == $prodId){
                if($item['total_quantity'] < $prodQuantity) return false;
            }
        }
        return true;
    }
}

?>