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

    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getLastBudgetNumber() { // OK
        $query = "SELECT MAX(budget_number) AS last_number FROM budget_versions";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch()['last_number'];
    }
    public function getLastBudgetVersion($number) { // OK
        $query = "SELECT MAX(version) AS last_version FROM budget_versions WHERE budget_number = $number";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch()['last_version'];
    }
    public function getLastBudgetVersionData($number) { // OK
        $query = "SELECT init_budget_id, old_budget_id, new_budget_id, budget_number, version 
                FROM budget_versions WHERE last_version = 1 AND budget_number = $number";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch();
    }
    public function getBudgets($filters, $orders, $onlyLastVersions = true) { // OK
        $sqlFilters = "";
        $sqlOrders = "";
        // Validación de filtros e inclusión en query
        if(!empty($filters->budgetNumber)) {
            $this->db->validateSanitizeId($filters->budgetNumber, "El número de la cotización es erróneo");
            $sqlFilters .= "WHERE bv.budget_number LIKE '%$filters->budgetNumber%'";
        }
        if(!empty($filters->user)) {
            $this->db->validateSanitizeString(str: $filters->user, wildcards: true, errorMsg: "El filtro de usuario es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "u.name LIKE '%$filters->user%'";
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
        // Última versión
        if($onlyLastVersions) {
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "bv.last_version = 1";
        }

        $sqlOrders = "ORDER BY bv.budget_number DESC"; // Provisorio

        $return = new stdClass();

        $query = "SELECT bv.init_budget_id, bv.old_budget_id, bv.new_budget_id, bv.budget_number, bv.version, bv.last_version,
                    b.budget_id, b.user_id, u.user AS user_name, c.name AS client_name, b.start_date, b.shipment_method_id, 
                    shipMet.title AS ship_method_name, b.payment_method_id, payMet.title AS pay_method_name, b.subtotal, 
                    b.total, b.description AS notes
                FROM budgets AS b 
                LEFT JOIN budget_versions AS bv ON b.budget_id = bv.new_budget_id 
                LEFT JOIN users AS u ON b.user_id = u.user_id
                LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
        // exit(json_encode($query));

        $this->db->query($query); 
        $this->db->validateLastQuery();
        $return->budgets = $this->db->fetchAll();

        $querySubtotal = "SELECT SUM(b.subtotal) AS subtotal
                        FROM budgets AS b 
                        LEFT JOIN budget_versions AS bv ON b.budget_id = bv.new_budget_id 
                        LEFT JOIN users AS u ON b.user_id = u.user_id
                        LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                        LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                        LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
        $this->db->query($querySubtotal); 
        $this->db->validateLastQuery();
        $return->subtotal = $this->db->fetch()['subtotal'];

        $queryShips = "SELECT SUM(b.ship) AS ships
                        FROM budgets AS b 
                        LEFT JOIN budget_versions AS bv ON b.budget_id = bv.new_budget_id 
                        LEFT JOIN users AS u ON b.user_id = u.user_id
                        LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                        LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                        LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
        $this->db->query($queryShips); 
        $this->db->validateLastQuery();
        $return->ships = $this->db->fetch()['ships'];

        $queryTotal = "SELECT SUM(b.total) AS total
                        FROM budgets AS b 
                        LEFT JOIN budget_versions AS bv ON b.budget_id = bv.new_budget_id 
                        LEFT JOIN users AS u ON b.user_id = u.user_id
                        LEFT JOIN shipment_methods AS shipMet ON b.shipment_method_id = shipMet.shipment_method_id
                        LEFT JOIN payment_methods AS payMet ON b.payment_method_id = payMet.payment_method_id
                        LEFT JOIN clients AS c ON b.client_id = c.client_id $sqlFilters $sqlOrders"; // MODIFICAR LIMIT ACA
        $this->db->query($queryTotal); 
        $this->db->validateLastQuery();
        $return->total = $this->db->fetch()['total'];

        return $return;
    }
    /* Recibe el id de la cotización (PK de budgets) */
    public function getBudgetInfo($id) { // OK
        $this->db->validateSanitizeId($id, "El identificador de la cotización es erróneo");
        $query = "SELECT bv.init_budget_id, bv.old_budget_id, bv.budget_number, bv.version, bv.last_version, 
                        b.budget_id, b.user_id, b.client_id, u.user AS user_name, c.name AS client_name, 
                        b.start_date, b.shipment_method_id, b.payment_method_id, b.description, b.subtotal, 
                        b.discount, b.tax, b.ship, sm.title AS ship_method_name, pm.title AS pay_method_name, 
                        b.total
                FROM budgets AS b 
                LEFT JOIN budget_versions AS bv ON bv.new_budget_id = b.budget_id
                LEFT JOIN users AS u ON b.user_id = u.user_id
                LEFT JOIN shipment_methods AS sm ON b.shipment_method_id = sm.shipment_method_id
                LEFT JOIN payment_methods AS pm ON b.payment_method_id = pm.payment_method_id
                LEFT JOIN clients AS c ON b.client_id = c.client_id 
                WHERE b.active = 1 AND b.budget_id = $id"; 
        $this->db->query($query);
        $this->db->validateLastQuery();
        if(!$this->db->numRows())
            return false;
        return $this->db->fetch();
    }
    /* Recibe el id de la cotización (PK de budgets) */
    public function getBudgetItems($id) { // OK
        $this->db->validateSanitizeId($id, "El identificador de la cotización es erróneo");
        $query = "SELECT bi.product_id, p.description, bi.sale_price, bi.quantity, bi.total_price, bi.position, bi.cost_price, si.quantity AS stock_quantity
                FROM budgets_items AS bi
                LEFT JOIN products AS p ON bi.product_id = p.product_id 
                LEFT JOIN stock_items AS si ON bi.product_id = si.product_id
                WHERE bi.budget_id = $id AND si.warehouse_id = 1"; // Falta ver si le corresponde campo active a cada ítem / Falta implementar más de 1 depósito
        $this->db->query($query);
        $this->db->validateLastQuery();
        if(!$this->db->numRows())
            return false;
        return $this->db->fetchAll(); 
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    private function getLastVersionBudgetId($number) { // OK
        $this->db->validateSanitizeId($number, "El número de cotización es erróneo");
        $query = "SELECT bv.new_budget_id, bv.budget_number, bv.version
                FROM budget_versions AS bv 
                WHERE bv.active = 1 AND bv.last_version = 1 AND bv.budget_number = $number"; // Revisar si aplica LIMIT 1 o es redundante
        $this->db->query($query);
        $this->db->validateLastQuery();
        if(!$this->db->numRows())
            return false;
        return $this->db->fetch()['new_budget_id'];
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    private function getNumberVersionBudgetId($number, $version) { // OK
        $this->db->validateSanitizeId($number, "El número de cotización es erróneo");
        $this->db->validateSanitizeId($version, "La versión de la cotización es errónea");
        $query = "SELECT bv.new_budget_id, bv.budget_number, bv.version
                FROM budget_versions AS bv 
                WHERE bv.active = 1 AND bv.version = $version AND bv.budget_number = $number"; // Revisar si aplica LIMIT 1 o es redundante
        $this->db->query($query);
        $this->db->validateLastQuery();
        if(!$this->db->numRows())
            return false;
        return $this->db->fetch()['new_budget_id'];
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    public function getLastVersionBudgetInfo($number) { // OK
        $budgetId = $this->getLastVersionBudgetId($number);
        return $this->getBudgetInfo($budgetId);
    }
    /* Recibe el número y la versión de la cotización (budget_numer y version de budget_versions) */
    public function getNumberVersionBudgetInfo($number, $version) { // OK
        $budgetId = $this->getNumberVersionBudgetId($number, $version);
        return $this->getBudgetInfo($budgetId);
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    public function getLastVersionBudgetItems($number) { // OK
        $budgetId = $this->getLastVersionBudgetId($number);
        return $this->getBudgetItems($budgetId);
    }
    /* Recibe el número y la versión de la cotización (budget_numer y version de budget_versions) */
    public function getNumberVersionBudgetItems($number, $version) { // OK
        $budgetId = $this->getNumberVersionBudgetId($number, $version);
        return $this->getBudgetItems($budgetId);
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    /* También se consulta el nro. de versión para usarlo como array key en getBudgetVersions */
    public function getBudgetVersionsIds($number, $withLastVersion = true) { // OK
        $this->db->validateSanitizeId($number, "El número de cotización es erróneo");
        $lastVersionFilter = "";
        if(!$withLastVersion)
            $lastVersionFilter = " AND bv.last_version = 0";
        $query = "SELECT b.budget_id, bv.version
                FROM budgets AS b
                LEFT JOIN budget_versions AS bv ON b.budget_id = bv.new_budget_id
                WHERE bv.budget_number = $number $lastVersionFilter
                ORDER BY bv.version ASC";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetchAll(); 
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    public function getBudgetVersions($number, $withLastVersion = false) { // Revisar $withLastVersion
        $versions = array();
        foreach($this->getBudgetVersionsIds($number, $withLastVersion) as $k => $v) {
            $versions[$v['version']] = new stdClass();
            $versions[$v['version']]->info  = $this->getBudgetInfo($v['budget_id']);
            $versions[$v['version']]->items = $this->getBudgetItems($v['budget_id']);
        }
        return $versions;
    }

    // VALIDACIONES
    /* Recibe el número de la cotización y su versión (budget_numer + version de budget_versions) */
    public function existNumberVersion($number, $version) { 
        $this->db->validateSanitizeId($number, "El número de cotización es erróneo");
        $this->db->validateSanitizeId($version, "El número de versión de la cotización es erróneo");
        $query = "SELECT bv.budget_number, bv.budget_version_id, bv.version
                FROM budget_versions AS bv
                WHERE bv.active = 1 AND bv.budget_number = $number AND bv.version = $version";
        $this->db->query($query);
        $this->db->validateLastQuery();
        if(empty($this->db->numRows())) 
            return false;
        
        $budget = $this->db->fetch();
        $ret = array(
            'number'  => $budget['budget_number'],
            'version' => $budget['version'],
        );
        return $ret;
    }
    /* Recibe el número de la cotización (budget_numer de budget_versions) */
    public function existNumber($number, $lastVersion = true) { 
        $this->db->validateSanitizeId($number, "El número de cotización es erróneo");
        $lastVFilter = ($lastVersion) ? "AND last_version = 1" : "";
        $query = "SELECT bv.budget_number, bv.budget_version_id, bv.version
                FROM budget_versions AS bv
                WHERE bv.active = 1 $lastVFilter AND bv.budget_number = $number";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return (empty($this->db->numRows()) ? false : $this->db->fetch());
    }
    /* Recibe el id de la cotización (PK de budgets) */
    public function exist($id) { // OK
        $this->db->validateSanitizeId($id, "El identificador de la cotización es erróneo");
        $query = "SELECT b.budget_id
                FROM budgets AS b
                WHERE b.active = 1 AND b.budget_id = $id";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return (empty($this->db->numRows()) ? false : $this->db->fetch()['budget_id']);
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newBudget($budget) { // OK
        $query   = "";
        $columns = "";
        $values  = "";

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
    public function newBudgetFirstVersion($budgetId) { // OK
        $this->db->validateSanitizeId($budgetId, "El identificador del presupuesto es inválido");
        $lastBudgetNumber = $this->getLastBudgetNumber(); 
        $lastBudgetNumber++;
        $query = "INSERT INTO budget_versions (init_budget_id, old_budget_id, new_budget_id, budget_number) 
                VALUES ($budgetId, $budgetId, $budgetId, $lastBudgetNumber)";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        return $lastBudgetNumber;
    }
    public function newBudgetLastVersion($budgetId, $number) { // ACA
        $this->db->validateSanitizeId($budgetId, "El identificador del presupuesto es inválido");
        $this->db->validateSanitizeId($number, "El numero del presupuesto es inválido");
        $lastBudgetVersion = $this->getLastBudgetVersionData($number); 
        /* Datos de la nueva versión */
        $init       = $lastBudgetVersion['init_budget_id'];
        $old        = $lastBudgetVersion['new_budget_id'];
        $version    = $lastBudgetVersion['version']+1;
        $queryInsert = "INSERT INTO budget_versions (init_budget_id, old_budget_id, new_budget_id, budget_number, version) 
                VALUES ($init, $old, $budgetId, $number, $version)";
        $this->db->query($queryInsert); 
        $this->db->validateLastQuery();
        $queryUpdate = "UPDATE budget_versions SET 
                    last_version = 0 WHERE new_budget_id = $old";
        $this->db->query($queryUpdate); 
        $this->db->validateLastQuery();
        return $version;
    }
    public function newBudgetItem($budgetItem, $budgetId) { // OK
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
    public function newBudgetItems($items, $budgetId) { // OK
        $ret = array();
        foreach($items as $k => $item) {
            $item->position = $k;
            $ret[] = $this->newBudgetItem($item, $budgetId);
        }
        return $ret;
    }
}

?>