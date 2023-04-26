<?php
//models/Budgets.php

require_once '../fw/fw.php';

class Budgets extends Model {
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll() { //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM budgets"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }

    public function getBudgets($filterValue) {
        // VALIDO FILTRO
        if(!empty($filterValue)) {
            $filterValue = substr($filterValue, 0, 50);
            $filterValue = $this->db->escape($filterValue);
            $filterValue = $this->db->escapeWildcards($filterValue);
        }

        $this->db->query("SELECT *
                            FROM view_budgets as b 
                            WHERE b.budget_id LIKE '%$filterValue%' OR 
                                    b.user_name LIKE '%$filterValue%' OR
                                    b.client_name LIKE '%$filterValue%' OR 
                                    b.total LIKE '%$filterValue%' OR
                                    b.start_date LIKE '%$filterValue%' OR
                                    b.description LIKE '%$filterValue%'"); // MODIFICAR LIMIT ACA
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }

    public function getBudgetItems($budgetId) {
        //Valido $budgetId
        if(!ctype_digit($budgetId)) throw new Exception("El id del presupuesto es erróneo");

        //QUERY SELECT 
        $this->db->query("SELECT *
                            FROM `view_budgets_items` 
                            WHERE sale_id = '$budgetId'");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    // A partir de acá se usan nuevas validaciones y sanitizaciones
    public function newBudget($budget) { 
        $this->db->validateSanitizeId($budget->client->client_id, "El identificador del cliente es inválido");
        $clientId = $budget->client->client_id;

        $this->db->validateSanitizeFloat($budget->totalPrice, "El precio total del presupuesto es inválido");
        $totalPrice = $budget->totalPrice;
        if($totalPrice < 0)  
            throw new Exception("El precio total de la cotización no puede ser menor a 0");
        if(!empty($budget->notes))
            $budget->notes = $this->db->escape($budget->notes);
        $userId = $_SESSION['user_id'];

        //QUERY INSERT
        $this->db->query("INSERT INTO budgets (user_id, client_id, total, description) 
                            VALUES ($userId, $clientId, $totalPrice, '$budget->notes')"); 
        $this->db->validateLastQuery();
        $lastBudget = $this->db->getLastInsertId();
        if(!$lastBudget)
            throw new Exception("Hubo un error al dar de alta el presupuesto");

        return $lastBudget;
    }

    public function newBudgetItem($budgetItem, $budgetId) { 
        $this->db->validateSanitizeId($budgetId, "El identificador del presupuesto es inválido");

        $prodId = (int)trim($budgetItem->product_id);
        $this->db->validateSanitizeId($prodId, "El identificador del producto #$prodId es inválido");

        $salePrice = (float)trim($budgetItem->sale_price);
        $this->db->validateSanitizeFloat($salePrice, "El precio del producto #$prodId es inválido");
        if($salePrice < 0)  
            throw new Exception("El precio del producto #$prodId no puede ser menor a 0");

        $costPrice = (float)trim($budgetItem->cost_price);
        $this->db->validateSanitizeFloat($costPrice, "El costo del producto #$prodId es inválido");
        if($costPrice < 0)  
            throw new Exception("El costo del producto #$prodId no puede ser menor a 0");

        $quantity = (float)trim($budgetItem->quantity);
        $this->db->validateSanitizeFloat($quantity, "La cantidad del producto #$prodId es inválida");
        if($quantity <= 0)  
            throw new Exception("La cantidad del producto #$prodId no puede ser menor o igual a 0");

        $totalPrice = (float)$salePrice * $quantity;

        $totalCost = (float)$costPrice * $quantity;

        $position = (int)trim($budgetItem->position);
        $this->db->validateSanitizeInt($position, "La posición del producto #$prodId es inválida"); // cambiar por SanitizeId (las posiciones arrancan en 1)

        //QUERY INSERT
        $q = "INSERT INTO budgets_items (budget_id, product_id, sale_price, cost_price, quantity, total_price, total_cost, position) 
                                        VALUES ($budgetId, $prodId, $salePrice, $costPrice, $quantity,
                                        $totalPrice, $totalCost, $position)";
        $this->db->query($q); 
        $this->db->validateLastQuery();
        $lastBudgetItem = $this->db->getLastInsertId();
        if(!$lastBudgetItem)
            throw new Exception("Hubo un error al dar de alta un ítem en el presupuesto " . $budgetId);

        return $lastBudgetItem;
    }

    public function newBudgetItems($items, $budgetId) { 
        $ret = array();
        foreach($items as $k => $item) {
            $item->position = $k;
            $ret[] = $this->newBudgetItem($item, $budgetId);
        }
        return $ret;
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