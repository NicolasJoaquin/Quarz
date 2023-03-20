<?php
//models/Buys.php

require_once '../fw/fw.php';

class Buys extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM buys"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }

    public function getBuys($filterValue){
        // VALIDO FILTRO
        if(!empty($filterValue)){
            $filterValue = substr($filterValue, 0, 100);
            $filterValue = $this->db->escape($filterValue);
            $filterValue = $this->db->escapeWildcards($filterValue);
        }

        $this->db->query("SELECT *
                            FROM view_buys as b 
                            WHERE b.buy_id LIKE '%$filterValue%' OR 
                                    b.user_name LIKE '%$filterValue%' OR
                                    b.provider_name LIKE '%$filterValue%' OR 
                                    b.total LIKE '%$filterValue%' OR
                                    b.start_date LIKE '%$filterValue%' OR
                                    b.ship_desc LIKE '%$filterValue%' OR
                                    b.pay_desc LIKE '%$filterValue%' OR
                                    b.description LIKE '%$filterValue%'"); // MODIFICAR LIMIT ACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    public function getBuy($buyId){
        //Valido $buyId
        if(!ctype_digit($buyId)) die ("error 1 getBuy/Buys (modelo)");

        $this->db->query("SELECT *
                            FROM view_buys as b 
                            WHERE b.buy_id = '$buyId'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetch();
    }

    public function getBuyItems($buyId){
        //Valido $buyId
        if(!ctype_digit($buyId)) die ("error 1 getBuyItems/Buys (modelo)");

        //QUERY SELECT 
        $this->db->query("SELECT *
                            FROM `view_buys_items` 
                            WHERE buy_id = '$buyId'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newBuy($buy){
        //Valido user_id
        if(!ctype_digit($buy->user_id)) die ("error 1 newBuy/Buys (modelo)");

        //Valido provider_id
        if(!ctype_digit($buy->provider_id)) die ("error 2 newBuy/Buys (modelo)");

        //Valido total
        if(!is_numeric($buy->total)) die ("error 3 newBuy/Buys (modelo)");

        //Valido description
        if(!empty($buy->description)){
            if(strlen($buy->description) > 255 ) die ("error 4 newBuy/Buys (modelo)");
            $buy->description = $this->db->escape($buy->description);
        }       

        //QUERY INSERT
        $this->db->query("INSERT INTO buys (user_id, provider_id, total, description) 
                            VALUES ($buy->user_id, $buy->provider_id, $buy->total, '$buy->description')"); // ver esta linea

        //VERIFICACIÓN DE LA QUERY INSERT
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  

        //TRAIGO LA ULTIMA VENTA
        $this->db->query("SELECT * FROM buys
                            ORDER BY buy_id DESC
                            LIMIT 1");
        $lastBuy = $this->db->fetch(); // ver si no me conviene hacer order by start_date

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $lastBuy;
    }

    public function newBuyItem($buyItem, $buyId){
        //Valido $buyId
        if(!ctype_digit($buyId)) die ("error 0 newBuyItem/Buys (modelo)");

        //Valido product_id
        if(!ctype_digit($buyItem->product_id)) die ("error 1 newBuyItem/Buys (modelo)");
        
        //Valido cost_price
        if(!is_numeric($buyItem->cost_price)) die ("error 3 newBuyItem/Buys (modelo)");

        //Valido quantity
        if(!ctype_digit($buyItem->quantity)) die ("error 4 newBuyItem/Buys (modelo)");       

        //Valido total_cost
        //if(!is_numeric($buyItem->total_cost)) die ("error 6 newBuyItem/Buys (modelo)"); // NO IRIA, LO CALCULA LA BS

        //Valido position
        if(!ctype_digit($buyItem->position)) die ("error 5 newBuyItem/Buys (modelo)");

        //QUERY INSERT
        $this->db->query("INSERT INTO buys_items (buy_id, product_id, cost_price, quantity, position) 
                            VALUES ('$buyId', '$buyItem->product_id', '$buyItem->cost_price', '$buyItem->quantity',
                                    '$buyItem->position')"); 

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());   
        return true;
    }

    public function newBuyItems($items, $buyId){
        if(empty($items)) die ("error 1 newSaleItems/Buys (modelo)");
        if(empty($buyId)) die ("error 2 newSaleItems/Buys (modelo)");

        foreach($items as $item){
            //if(!$this->newBuyItem($item, $buyId)) return false; // ESTA LINEA NO IRÍA, LE DEVUELVO AL CONTROLADOR DIRECTAMENTE LA EXCEPCION DE newBuyItem
            $this->newBuyItem($item, $buyId);
        }
        return true;
    }

    public function updateBuy($buy){
        //Valido id
        if(!ctype_digit($buy->id)) die ("error 0 updateBuy/Buys (modelo)");

        //Valido ship_id
        if(!ctype_digit($buy->ship_id)) die ("error 2 updateBuy/Buys (modelo)");

        //Valido pay_id
        if(!ctype_digit($buy->pay_id)) die ("error 3 updateBuy/Buys (modelo)");    

        //Valido description
        if(!empty($buy->description)){
            if(strlen($buy->description) > 255 ) die ("error 4 updateBuy/Buys (modelo)");
            $buy->description = $this->db->escape($buy->description);
        }  

         //QUERY UPDATE
         $this->db->query("UPDATE buys
                            SET shipment_state_id = $buy->ship_id, payment_state_id = $buy->pay_id, description = '$buy->description'
                            WHERE buy_id = $buy->id");
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

    public function deleteBuy($buyId){ // DIVIDIR ESTE METODO EN ITEMS X UN LADO Y SALES POR EL OTRO, PASAR LOGICA A CONTROLADOR
        //Valido id
        if(!ctype_digit($buyId)) die ("error 1 deleteBuy/Buys (modelo)");

        //QUERY DELETE
        $this->db->query("DELETE FROM buys_items 
                            WHERE buy_id = '$buyId'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());           

        //QUERY DELETE
        $this->db->query("DELETE FROM buys 
                            WHERE buy_id = '$buyId'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  

        return true;
    }

    //VALIDADORES------------------------------------------------------------------------------------------------------------------
    private function validateItem($item, $buyId){ // EN ESTE METODO RETORNO FALSO PORQUE LO USO EN UN IF EN CONTROLADOR
        //Valido product_id
        if(!ctype_digit($item->product_id)) die ("error 1 validateItem/Buys (modelo)");

        //Valido $buyId
        if(!ctype_digit($buyId)) die ("error 2 validateItem/Buys (modelo)");

        $q = "SELECT * FROM products WHERE product_id = '$item->product_id'";
        $this->db->query($q);

        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());

        if(!$product = $this->db->fetch()) die ("error 3 validateItem/Buys (modelo)");

        $q = "SELECT * FROM buys WHERE buy_id = '$buyId'";
        $this->db->query($q);

        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());

        if(!$buy = $this->db->fetch()) die ("error 4 validateItem/Buys (modelo)");

        if($product['provider_id'] != $buy['provider_id']) return false;

        return true;
    }

    public function validateItems($items, $buyId){
        if(empty($items)) die ("error 1 validateItems/Sales (modelo)");
        if(empty($buyId)) die ("error 2 validateItems/Sales (modelo)");

        foreach($items as $item){
            if(!$this->validateItem($item, $buyId)) return false;
        }
        return true;
    }
}

?>