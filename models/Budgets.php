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

    // public function getBudgets($filterValue) {
    //     // VALIDO FILTRO
    //     if(!empty($filterValue)) {
    //         $filterValue = substr($filterValue, 0, 50);
    //         $filterValue = $this->db->escape($filterValue);
    //         $filterValue = $this->db->escapeWildcards($filterValue);
    //     }

    //     $this->db->query("SELECT *
    //                         FROM view_budgets as b 
    //                         WHERE b.budget_id LIKE '%$filterValue%' OR 
    //                                 b.user_name LIKE '%$filterValue%' OR
    //                                 b.client_name LIKE '%$filterValue%' OR 
    //                                 b.total LIKE '%$filterValue%' OR
    //                                 b.start_date LIKE '%$filterValue%' OR
    //                                 b.description LIKE '%$filterValue%'"); // MODIFICAR LIMIT ACA
    //     //VERIFICACIÓN DE LA QUERY Y RETORNO
    //     $this->db->validateLastQuery();
    //     return $this->db->fetchAll();
    // }

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

    // A partir de acá se usan nuevas validaciones y sanitizaciones
        // VALIDACIONES Y SANITIZACIONES
        // public function validateFilters(&$filters) {

        // }

        //GETERS------------------------------------------------------------------------------------------------------------------------
        public function getBudgets($filters, $orders) {
            $sqlFilters = "";
            $sqlOrders = "";
            // Validación de filtros e inclusión en query
            if(!empty($filters->budgetNumber)) {
                $this->db->validateSanitizeId($filters->budgetNumber, "El número de la cotización es erróneo");
                $sqlFilters .= "WHERE b.budget_id LIKE '%$filters->budgetNumber%'";
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

            // ACÁ VA EL FILTRO DE ACTIVIDAD DE LA COTIZACIÓN (REGULADO POR TIEMPO)
            // SE EVITAN FILTROS DE VERSIONES POR EL MOMENTO
            
            if(!empty($filters->fromDate)) {
                $this->db->validateSanitizeDate(date: $filters->fromDate, format: "Y-m-d H:i:s", errorMsg: "El filtro de fecha de inicio es erróneo");
                if(!empty($sqlFilters))
                    $sqlFilters .= " AND ";
                else
                    $sqlFilters .= "WHERE ";
                $sqlFilters .= "b.start_date >= '$filters->fromDate'";
            }
            if(!empty($filters->toDate)) {
                $this->db->validateSanitizeDate(date: $filters->toDate, format: "Y-m-d H:i:s", errorMsg: "El filtro de fecha de inicio es erróneo");
                if(!empty($sqlFilters))
                    $sqlFilters .= " AND ";
                else
                    $sqlFilters .= "WHERE ";
                $sqlFilters .= "b.start_date <= '$filters->toDate'";
            }
            if(!empty($filters->fromDate) && !empty($filters->toDate) && $filters->fromDate > $filters->toDate)
                throw new Exception("La fecha de inicio no puede ser posterior a la de final");
            // FALTA AGREGAR ESTE FILTRO A DASHBOARD DE VENTAS (MÉTODOS DE ENVÍO Y PAGO)
            if(!empty($filters->shipmentMethod)) {
                $this->db->validateSanitizeId($filters->shipmentMethod, "El filtro de medio de envío es erróneo");
                if(!empty($sqlFilters))
                    $sqlFilters .= " AND ";
                else
                    $sqlFilters .= "WHERE ";
                $sqlFilters .= "b.shipment_method_id = $filters->shipmentMethod";
            }
            if(!empty($filters->paymentMethod)) {
                $this->db->validateSanitizeId($filters->paymentMethod, "El filtro de medio de pago es erróneo");
                if(!empty($sqlFilters))
                    $sqlFilters .= " AND ";
                else
                    $sqlFilters .= "WHERE ";
                $sqlFilters .= "b.payment_method_id = $filters->paymentMethod";
            }

            if(!empty($filters->subtotal)) { // Revisar validación (por ahora string)
                $this->db->validateSanitizeString(str: $filters->subtotal, wildcards: true, errorMsg: "El filtro de subtotal es erróneo");
                if(!empty($sqlFilters))
                    $sqlFilters .= " AND ";
                else
                    $sqlFilters .= "WHERE ";
                $sqlFilters .= "b.subtotal LIKE '%$filters->subtotal%'";
            }
            if(!empty($filters->total)) { // Revisar validación (por ahora string)
                $this->db->validateSanitizeString(str: $filters->total, wildcards: true, errorMsg: "El filtro de total es erróneo");
                if(!empty($sqlFilters))
                    $sqlFilters .= " AND ";
                else
                    $sqlFilters .= "WHERE ";
                $sqlFilters .= "b.total LIKE '%$filters->total%'";
            }
            // Activo (alta/baja lógica)
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "b.active = 1";
    
            $sqlOrders = "ORDER BY b.budget_id DESC"; // Provisorio

            $return = new stdClass();

            $query = "SELECT b.budget_id, b.user_id, u.user AS user_name, c.name AS client_name, b.start_date, b.shipment_method_id, 
                        shipMet.title AS ship_method_name, b.payment_method_id, payMet.title AS pay_method_name, b.subtotal, 
                        b.total, b.description AS notes
                    FROM budgets AS b 
                    LEFT JOIN users AS u ON b.user_id = u.user_id
                    LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                    LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                    LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
            
            $this->db->query($query); 
            $this->db->validateLastQuery();
            $return->budgets = $this->db->fetchAll();

            $querySubtotal = "SELECT SUM(b.subtotal) AS subtotal
                            FROM budgets AS b 
                            LEFT JOIN users AS u ON b.user_id = u.user_id
                            LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                            LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                            LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
            $this->db->query($querySubtotal); 
            $this->db->validateLastQuery();
            $return->subtotal = $this->db->fetch()['subtotal'];

            $queryShips = "SELECT SUM(b.ship) AS ships
                            FROM budgets AS b 
                            LEFT JOIN users AS u ON b.user_id = u.user_id
                            LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                            LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                            LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
            $this->db->query($queryShips); 
            $this->db->validateLastQuery();
            $return->ships = $this->db->fetch()['ships'];


            $queryTotal = "SELECT SUM(b.total) AS total
                            FROM budgets AS b 
                            LEFT JOIN users AS u ON b.user_id = u.user_id
                            LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                            LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                            LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
            $this->db->query($queryTotal); 
            $this->db->validateLastQuery();
            $return->total = $this->db->fetch()['total'];

            return $return;
        }
    
    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newBudget($budget, $initVersion = true) { 
        $query   = "";
        $columns = "";
        $values  = "";
        if(!$initVersion) {
            $this->db->validateSanitizeId($budget->init_version, "El identificador de la versión inicial es inválido");
            $querySelect = "SELECT version FROM budgets WHERE init_version_id = $budget->init_version ORDER BY version DESC LIMIT 1"; // Nro. de última versión de esta cotización
            $this->db->query($querySelect);
            $this->db->validateLastQuery();
            $lastVersion = $this->db->fetch()['version'];
            $lastVersion++;
            $columns .= "init_version_id, version"; 
            $values .= "$budget->init_version, $lastVersion"; 
        }

        if(!empty($columns) && !empty($values)) {
            $columns .= ", ";
            $values  .= ", ";
        }
        $columns .= "user_id";
        $values  .= $_SESSION['user_id'];

        $this->db->validateSanitizeId($budget->client->client_id, "El identificador del cliente es inválido");
        $columns .= ", client_id";
        $values  .= ", " . $budget->client->client_id;

        $this->db->validateSanitizeFloat($budget->subtotalPrice, "El precio subtotal del presupuesto es inválido");
        if($budget->subtotalPrice < 0)  
            throw new Exception("El precio subtotal de la cotización no puede ser menor a 0");
        $columns .= ", subtotal";
        $values  .= ", " . $budget->subtotalPrice;

        if(!empty($budget->discount)) {
            $this->db->validateSanitizeFloat($budget->discount, "El descuento del presupuesto es inválido");
            if($budget->discount < 0)  
                throw new Exception("El descuento de la cotización no puede ser menor a 0");
            $columns .= ", discount";
            $values  .= ", " . $budget->discount;
        }

        if(!empty($budget->tax)) {
            $this->db->validateSanitizeFloat($budget->tax, "Los recargos o impuestos del presupuesto son inválidos");
            if($budget->tax < 0)  
                throw new Exception("Los recargos o impuestos de la cotización no pueden ser menor a 0");
            $columns .= ", tax";
            $values  .= ", " . $budget->tax;
        }

        if(!empty($budget->ship)) {
            $this->db->validateSanitizeFloat($budget->ship, "El precio de envío del presupuesto es inválido");
            if($budget->ship < 0)  
                throw new Exception("El precio de envío de la cotización no pueden ser menor a 0");
            $columns .= ", ship";
            $values  .= ", " . $budget->ship;
        }

        $this->db->validateSanitizeFloat($budget->totalPrice, "El precio total del presupuesto es inválido");
        if($budget->totalPrice < 0)  
            throw new Exception("El precio total de la cotización no puede ser menor a 0");
        $columns .= ", total";
        $values  .= ", " . $budget->totalPrice;

        if(!empty($budget->shipMethod->shipment_method_id)) {
            $this->db->validateSanitizeId($budget->shipMethod->shipment_method_id, "El medio de envío del presupuesto es inválido");
            $columns .= ", shipment_method_id";
            $values  .= ", " . $budget->shipMethod->shipment_method_id;
        }

        if(!empty($budget->payMethod->payment_method_id)) {
            $this->db->validateSanitizeId($budget->payMethod->payment_method_id, "El medio de pago del presupuesto es inválido");
            $columns .= ", payment_method_id";
            $values  .= ", " . $budget->payMethod->payment_method_id;
        }

        if(!empty($budget->notes)) {
            $this->db->validateSanitizeString(str: $budget->notes, errorMsg: "Las notas son inválidas", maxLen: 350);
            $columns .= ", description";
            $values  .= ", '" . $budget->notes . "'";
        }

        $query = "INSERT INTO budgets ($columns) VALUES ($values)";

        //QUERY INSERT
        $this->db->query($query); 
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