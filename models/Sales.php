<?php
//models/Sales.php

require_once '../fw/fw.php';

class Sales extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM sales"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }

    public function getSales($filterValue){
        // VALIDO FILTRO
        if(!empty($filterValue)){
            $filterValue = substr($filterValue, 0, 50);
            $filterValue = $this->db->escape($filterValue);
            $filterValue = $this->db->escapeWildcards($filterValue);
        }

        $this->db->query("SELECT *
                            FROM view_sales as s 
                            WHERE s.sale_id LIKE '%$filterValue%' OR 
                                    s.user_name LIKE '%$filterValue%' OR
                                    s.client_name LIKE '%$filterValue%' OR 
                                    s.total LIKE '%$filterValue%' OR
                                    s.start_date LIKE '%$filterValue%' OR
                                    s.ship_desc LIKE '%$filterValue%' OR
                                    s.pay_desc LIKE '%$filterValue%' OR
                                    s.description LIKE '%$filterValue%'"); // MODIFICAR LIMIT ACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    public function getSaleItems($saleId){
        //Valido $saleId
        if(!ctype_digit($saleId)) die ("error 1 getSaleItems/Sales (modelo)");

        //QUERY SELECT 
        $this->db->query("SELECT *
                            FROM `view_sales_items` 
                            WHERE sale_id = '$saleId'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newSale($sale, $userId){
        //Valido userId
        if(!ctype_digit($userId)) die ("error 1 newSale/Sales (modelo)");

        //Valido client_id
        if(!ctype_digit($sale->client_id)) die ("error 2 newSale/Sales (modelo)");

        //Valido total
        if(!is_numeric($sale->total)) die ("error 3 newSale/Sales (modelo)");

        //Valido description
        if(!empty($sale->description)){
            if(strlen($sale->description) > 255 ) die ("error 4 newSale/Sales (modelo)");
            $sale->description = $this->db->escape($sale->description);
        }       

        //QUERY INSERT
        $this->db->query("INSERT INTO sales (user_id, client_id, total, description) 
                            VALUES ($userId, $sale->client_id, $sale->total, '$sale->description')"); // ver esta linea

        //VERIFICACIÓN DE LA QUERY INSERT
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  

        //TRAIGO LA ULTIMA VENTA
        $this->db->query("SELECT * FROM sales
                            ORDER BY sale_id DESC
                            LIMIT 1");
        $lastSale = $this->db->fetch(); // ver si no me conviene hacer order by start_date

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) return false;
        return $lastSale;
    }

    public function newSaleItem($saleItem, $saleId){
        //Valido $saleId
        if(!ctype_digit($saleId)) die ("error 0 newSaleItem/Sales (modelo)");

        //Valido prodId
        if(!ctype_digit($saleItem->prodId)) die ("error 1 newSaleItem/Sales (modelo)");

        //Valido prodPrice
        if(!is_numeric($saleItem->prodPrice)) die ("error 2 newSaleItem/Sales (modelo)");
        
        //Valido prodCost
        if(!is_numeric($saleItem->prodCost)) die ("error 3 newSaleItem/Sales (modelo)");

        //Valido prodQuantity
        if(!ctype_digit($saleItem->prodQuantity)) die ("error 4 newSaleItem/Sales (modelo)");       

        //Valido totalPrice
        if(!is_numeric($saleItem->totalPrice)) die ("error 5 newSaleItem/Sales (modelo)");

        //Valido totalCost
        if(!is_numeric($saleItem->totalCost)) die ("error 6 newSaleItem/Sales (modelo)");

        //Valido position
        if(!ctype_digit($saleItem->position)) die ("error 5 newSaleItem/Sales (modelo)");

        //QUERY INSERT
        $this->db->query("INSERT INTO sales_items (sale_id, product_id, sale_price, cost_price, quantity, total_price, total_cost, position) 
                            VALUES ('$saleId', '$saleItem->prodId', '$saleItem->prodPrice', '$saleItem->prodCost',
                                        '$saleItem->prodQuantity', '$saleItem->totalPrice', '$saleItem->totalCost', '$saleItem->position')"); 

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) return false;  
        return true;
    }

    public function newSaleItems($items, $saleId){
        if(empty($items)) die ("error 1 newSaleItems/Sales (modelo)");
        if(empty($saleId)) die ("error 2 newSaleItems/Sales (modelo)");

        foreach($items as $item){
            if(!$this->newSaleItem($item, $saleId)) return false;
        }
        return true;
    }

    public function updateSale($sale){
        //Valido id
        if(!ctype_digit($sale->id)) die ("error 0 updateSale/Sales (modelo)");

        //Valido client_id (CASO REASIGNACIÓN DE CLIENTE)
        //if(!ctype_digit($sale->client_id)) die ("error 1 updateSale/Sales (modelo)");

        //Valido ship_id
        if(!ctype_digit($sale->ship_id)) die ("error 2 updateSale/Sales (modelo)");

        //Valido pay_id
        if(!ctype_digit($sale->pay_id)) die ("error 3 updateSale/Sales (modelo)");    

        //Valido description
        if(!empty($sale->description)){
            if(strlen($sale->description) > 255 ) die ("error 4 updateSale/Sales (modelo)");
            $sale->description = $this->db->escape($sale->description);
        }  

         //QUERY UPDATE
         $this->db->query("UPDATE sales
                            SET shipment_state_id = $sale->ship_id, payment_state_id = $sale->pay_id, description = '$sale->description'
                            WHERE sale_id = $sale->id");
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

    public function deleteSale($sale_id){ // DIVIDIR ESTE METODO EN ITEMS X UN LADO Y SALES POR EL OTRO, PASAR LOGICA A CONTROLADOR
        //Valido id
        if(!ctype_digit($sale_id)) die ("error 1 deleteSale/Sale (modelo)");

        //QUERY DELETE
        $this->db->query("DELETE FROM sales_items 
                            WHERE sale_id = '$sale_id'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) die("error 2 deleteSale/Sale (modelo) ". $sale_id . $this->db->getError());         

        //QUERY DELETE
        $this->db->query("DELETE FROM sales 
                            WHERE sale_id = '$sale_id'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) die("error 3 deleteSale/Sale (modelo) ". $sale_id . $this->db->getError());  

        return true;
    }
}

?>