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

    // Modificado desde acá
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

    public function discountQuantity($quantity, $prodId) { //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO 
        $this->validateItem($quantity, $prodId);

        //QUERY UPDATE Y VERIFICACIÓN
        $updateQuery = "UPDATE stock_items AS s SET s.quantity = s.quantity - $quantity WHERE product_id = $prodId";
        $this->db->query($updateQuery);
        $this->db->validateLastQuery();
        return true;
    }

    public function discountQuantities($items) {
        foreach($items as $item) {
            $this->discountQuantity($item->quantity, $item->product_id);
        }
        return true;
    }

    public function discountValidatedQuantity($quantity, $prodId) { 
        if(!$this->validateStockItem($quantity, $prodId))
            throw new Exception("El stock del producto #$prodId no es suficiente");

        $this->discountQuantity($quantity, $prodId);
        return true;
    }

    public function discountValidatedQuantities($items) { 
        $this->validateStockItems($items);
        $this->discountQuantities($items);
        return true;
    }

    public function registerStockChanges($saleId) {
        // Items de la venta
        $this->db->query("SELECT si.sale_item_id, si.sale_id, si.product_id, si.quantity, s.user_id
                        FROM sales_items AS si
                        LEFT JOIN sales AS s ON si.sale_id = s.sale_id
                        WHERE s.sale_id = $saleId"); 
        $this->db->validateLastQuery();
        $salesItems = $this->db->fetchAll();
        foreach($salesItems as $saleItem) {
            $saleItemId = $saleItem['sale_item_id'];
            $prodId     = $saleItem['product_id'];
            $quantity   = $saleItem['quantity'];
            $user       = $saleItem['user_id']; 
            // Stock anterior del producto
            $this->db->query("SELECT stock_item_id, quantity
                            FROM stock_items 
                            WHERE product_id = $prodId AND warehouse_id = 1"); // Sólo depósito 1 por ahora
            $this->db->validateLastQuery();
            $stockItem   = $this->db->fetch();
            $stockItemId = $stockItem['stock_item_id'];
            $oldQuantity = $stockItem['quantity'];

            $newQuantity = $oldQuantity - $quantity;
            // Guardar cambio y descontar stock
            $this->db->query("INSERT INTO stock_changes (user_id, sale_item_id, stock_item_id, quantity, old_quantity) 
                            VALUES ($user, $saleItemId, $stockItemId, $newQuantity, $oldQuantity)");
            $this->db->validateLastQuery();

            $this->discountQuantity($quantity, $prodId);
        }
        return true;
    }

    //VERIFICADORES------------------------------------------------------------------------------------------------------------------
    public function validateStockItem($quantity, $prodId) { 
        $this->validateItem($quantity, $prodId);

        if($quantity <= 0)  
            throw new Exception("La cantidad del producto #$prodId no puede ser menor o igual a 0, no se puede descontar del stock");

        // $query = "SELECT product_id, SUM(quantity) AS total_quantity 
        //             FROM `stock_items` WHERE product_id = $prodId 
        //             GROUP BY product_id"; // Esto aplica para todos los depósitos, deshabilitado por ahora
        $query = "SELECT product_id, quantity AS total_quantity 
                    FROM stock_items WHERE product_id = $prodId AND warehouse_id = 1
                    GROUP BY product_id"; // Sólo depósito 1
        $this->db->query($query);
        $this->db->validateLastQuery();
        $stockQuantity = $this->db->fetch()['total_quantity'];
        if($stockQuantity < $quantity) return false;
        return true;
    }

    public function validateStockItems($items) { 
        foreach($items as $item) {
            $quantity = 0;
            $prodId   = $item->product_id;
            $prodDesc = $item->description;    
            foreach($items as $item2) {
                if($prodId == $item2->product_id) 
                    $quantity += $item2->quantity;
            }
            if(!$this->validateStockItem($quantity, $prodId))
                throw new Exception("El stock del producto #$prodId- $prodDesc no es suficiente.");
        }
        return true;
    }

    public function validateItem(&$quantity, &$prodId) {
        $prodId = (int)trim($prodId);
        $this->db->validateSanitizeId($prodId, "El identificador del producto #$prodId es inválido");

        $quantity = (float)trim($quantity);
        $this->db->validateSanitizeFloat($quantity, "La cantidad del producto #$prodId es inválida");

        return true;
    }

    public function validateItems(&$items) { 
        foreach($items as $item) {
            if(!$this->validateItem($item->quantity, $item->product_id))
                return false;
        }
        return true;
    }
    // Hasta acá










    public function addQuantity($item){ //FALTA IMPLEMENTACIÓN PARA MÁS DE UN DEPÓSITO / Revisar
        //Valido product_id
        if(!ctype_digit($item['product_id'])) die ("error 0 addQuantity/Stock (modelo)");

        //Valido quantity
        if(!ctype_digit($item['quantity'])) die ("error 1 addQuantity/Stock (modelo)");

        
        $product_id = $item['product_id']; 
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