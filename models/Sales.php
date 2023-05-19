<?php
//models/Sales.php

require_once '../fw/fw.php';

class Sales extends Model {
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM sales"); // MODIFICAR PARA VER VISTA
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
    // A partir de acá se usan nuevas validaciones y sanitizaciones
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getSales($filters, $orders) {
        $sqlFilters = "";
        $sqlOrders = "";
        // Validación de filtros e inclusión en query
        if(!empty($filters->saleNumber)) {
            $this->db->validateSanitizeId($filters->saleNumber, "El número de la venta es erróneo");
            $sqlFilters .= "WHERE s.sale_id LIKE '%$filters->saleNumber%'";
        }
        if(!empty($filters->user)) {
            $this->db->validateSanitizeString(str: $filters->user, wildcards: true, errorMsg: "El filtro de usuario es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "u.user_name LIKE '%$filters->user%'";
        }
        if(!empty($filters->client)) {
            $this->db->validateSanitizeString(str: $filters->client, wildcards: true, errorMsg: "El filtro de cliente es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "c.name LIKE '%$filters->client%'";
        }
        if(!empty($filters->budget)) {
            $this->db->validateSanitizeId($filters->budget, "El número del presupuesto es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.budget_id LIKE '%$filters->budget%'";
        }
        if(!empty($filters->fromDate)) {
            $this->db->validateSanitizeDate(date: $filters->fromDate, format: "Y-m-d H:i:s", errorMsg: "El filtro de fecha de inicio es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.start_date >= '$filters->fromDate'";
        }
        if(!empty($filters->toDate)) {
            $this->db->validateSanitizeDate(date: $filters->toDate, format: "Y-m-d H:i:s", errorMsg: "El filtro de fecha de inicio es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.start_date <= '$filters->toDate'";
        }
        if(!empty($filters->fromDate) && !empty($filters->toDate) && $filters->fromDate > $filters->toDate)
            throw new Exception("La fecha de inicio no puede ser posterior a la de final");
        if(!empty($filters->shipment)) {
            $this->db->validateSanitizeId($filters->shipment, "El filtro de envío es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.shipment_state_id = $filters->shipment";
        }
        if(!empty($filters->payment)) {
            $this->db->validateSanitizeId($filters->payment, "El filtro de envío es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.payment_state_id = $filters->payment";
        }

        $sqlOrders = "ORDER BY s.sale_id DESC"; // Provisorio
        
        $this->db->query("SELECT s.sale_id, s.user_id, u.user AS user_name, c.name AS client_name, s.budget_id, s.start_date, s.shipment_state_id, ship.title AS ship_name, s.payment_state_id, pay.title AS pay_name
                            FROM sales AS s 
                            LEFT JOIN users AS u ON s.user_id = u.user_id
                            LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
                            LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
                            LEFT JOIN clients AS c ON s.client_id = c.client_id $sqlFilters $sqlOrders"); // MODIFICAR LIMIT ACA
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }

    public function getSaleInfo($saleId) {
        $this->db->validateSanitizeId($saleId, "El número de venta es erróneo");
        $query = "SELECT s.sale_id, s.user_id, u.user AS user_name, c.name AS client_name, s.budget_id, s.start_date, s.shipment_state_id, ship.title AS ship_name, s.payment_state_id, pay.title AS pay_name, s.description, s.total
                FROM sales AS s 
                LEFT JOIN users AS u ON s.user_id = u.user_id
                LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
                LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
                LEFT JOIN clients AS c ON s.client_id = c.client_id 
                WHERE s.sale_id = $saleId"; 
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch();
    }
    public function getSaletems($saleId) {
        $this->db->validateSanitizeId($saleId, "El número de venta es erróneo");
        $query = "SELECT si.product_id, p.description AS product_name, si.sale_price, si.quantity, si.total_price, si.position 
                FROM sales_items AS si
                LEFT JOIN products AS p ON si.product_id = p.product_id 
                WHERE si.sale_id = $saleId";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetchAll(); 
    }

    // Altas, bajas y modificaciones
    public function newSale($sale) {
        $this->db->validateSanitizeId($sale->client->client_id, "El identificador del cliente es inválido");
        $clientId = $sale->client->client_id;

        $this->db->validateSanitizeFloat($sale->totalPrice, "El precio total de la venta es inválido");
        $totalPrice = $sale->totalPrice;
        if($totalPrice < 0)  
            throw new Exception("El precio total de la venta no puede ser menor a 0");
        if(!empty($sale->notes))
            $sale->notes = $this->db->escape($sale->notes);
        $userId = $_SESSION['user_id'];       

        //QUERY INSERT
        $this->db->query("INSERT INTO sales (user_id, client_id, total, description) 
                            VALUES ($userId, $clientId , $totalPrice, '$sale->notes')");
        $this->db->validateLastQuery();
        $lastSale = $this->db->getLastInsertId();
        if(!$lastSale)
            throw new Exception("Hubo un error al dar de alta la venta");

        return $lastSale;
    }

    public function newSaleItem($saleItem, $saleId){
        $this->db->validateSanitizeId($saleId, "El identificador de la venta es inválida");

        $prodId = (int)trim($saleItem->product_id);
        $this->db->validateSanitizeId($prodId, "El identificador del producto #$prodId es inválido");

        $salePrice = (float)trim($saleItem->sale_price);
        $this->db->validateSanitizeFloat($salePrice, "El precio del producto #$prodId es inválido");
        if($salePrice < 0)  
            throw new Exception("El precio del producto #$prodId no puede ser menor a 0");

        $costPrice = (float)trim($saleItem->cost_price);
        $this->db->validateSanitizeFloat($costPrice, "El costo del producto #$prodId es inválido");
        if($costPrice < 0)  
            throw new Exception("El costo del producto #$prodId no puede ser menor a 0");

        $quantity = (float)trim($saleItem->quantity);
        $this->db->validateSanitizeFloat($quantity, "La cantidad del producto #$prodId es inválida");
        if($quantity <= 0)  
            throw new Exception("La cantidad del producto #$prodId no puede ser menor o igual a 0");

        $totalPrice = (float)$salePrice * $quantity;

        $totalCost = (float)$costPrice * $quantity;

        $position = (int)trim($saleItem->position);
        $this->db->validateSanitizeInt($position, "La posición del producto #$prodId es inválida"); // cambiar por SanitizeId (las posiciones arrancan en 1)

        //QUERY INSERT
        $this->db->query("INSERT INTO sales_items (sale_id, product_id, sale_price, cost_price, quantity, total_price, total_cost, position) 
                            VALUES ($saleId, $prodId, $salePrice, $costPrice, $quantity,
                            $totalPrice, $totalCost, $position)"); 
        $this->db->validateLastQuery();
        $lastSaleItem = $this->db->getLastInsertId();
        if(!$lastSaleItem)
            throw new Exception("Hubo un error al dar de alta un ítem en el presupuesto " . $saleId);

        return $lastSaleItem;
    }

    public function newSaleItems($items, $saleId){
        $ret = array();
        foreach($items as $k => $item) {
            $item->position = $k;
            $ret[] = $this->newSaleItem($item, $saleId);
        }
        return $ret;
    }

    // Hasta acá
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