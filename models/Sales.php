<?php
//models/Sales.php

require_once '../fw/fw.php';

class Sales extends Model {
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll() { //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM sales"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }
    public function getSales($filters, $orders, $limitOffset, $limitLength) {
        $sqlFilters = "";
        $sqlOrders = "";
        $sqlLimit = "";
        // Validación de limit
        $this->db->validateSanitizeInt($limitOffset, "El límite (offset) es erróneo");
        $this->db->validateSanitizeInt($limitLength, "El límite (length) es erróneo");
        if($limitOffset < 0)
            throw new Exception("El límite (offset) no puede ser menor a 0");
        if($limitLength < 0)
            throw new Exception("El límite (length) no puede ser menor a 0");
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
            $sqlFilters .= "u.user LIKE '%$filters->user%'";
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
            $sqlFilters .= "v.budget_number LIKE '%$filters->budget%'";
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
            $this->db->validateSanitizeId($filters->payment, "El filtro de pago es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.payment_state_id = $filters->payment";
        }
        // Activo (alta/baja lógica)
        if(!empty($sqlFilters))
            $sqlFilters .= " AND ";
        else
            $sqlFilters .= "WHERE ";
        $sqlFilters .= "s.active = 1";

        $sqlOrders = "ORDER BY s.sale_id DESC"; // Provisorio

        $sqlLimit = "LIMIT $limitOffset,$limitLength";

        $query = "SELECT s.sale_id, s.user_id, u.user AS user_name, c.client_id, c.name AS client_name, s.budget_id, v.budget_number, v.version AS budget_version, 
            s.start_date, s.shipment_state_id, ship.title AS ship_name, s.payment_state_id, nextShip.title AS next_shipment_state, 
            pay.title AS pay_name, nextPay.title AS next_payment_state, s.total, s.description AS notes
                FROM sales AS s 
                LEFT JOIN users AS u ON s.user_id = u.user_id
                LEFT JOIN budget_versions AS v ON v.new_budget_id = s.budget_id 
                LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
                LEFT JOIN shipment_states AS nextShip ON ship.next_step = nextShip.shipment_state_id
                LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
                LEFT JOIN payment_states AS nextPay ON pay.next_step = nextPay.payment_state_id
                LEFT JOIN clients AS c ON s.client_id = c.client_id $sqlFilters $sqlOrders $sqlLimit";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }
    public function getSalesIds($filters, $limitOffset, $limitLength) {
        $sqlFilters = "";
        $sqlLimit = "";
        // Validación de limit
        $this->db->validateSanitizeInt($limitOffset, "El límite (offset) es erróneo");
        $this->db->validateSanitizeInt($limitLength, "El límite (length) es erróneo");
        if($limitOffset < 0)
            throw new Exception("El límite (offset) no puede ser menor a 0");
        if($limitLength < 0)
            throw new Exception("El límite (length) no puede ser menor a 0");
        // Validación de filtros e inclusión en query
        if(!empty($filters->client_id)) {
            $this->db->validateSanitizeString($filters->client_id, "El número del cliente es erróneo");
            if(!is_numeric($filters->client_id)) throw new Exception("El número del cliente es erróneo");
            $sqlFilters .= "WHERE c.client_id LIKE '%$filters->client_id%'";
        }
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
            $sqlFilters .= "u.user LIKE '%$filters->user%'";
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
            $sqlFilters .= "v.budget_number LIKE '%$filters->budget%'";
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
        // Activo (alta/baja lógica)
        if(!empty($sqlFilters))
            $sqlFilters .= " AND ";
        else
            $sqlFilters .= "WHERE ";
        $sqlFilters .= "s.active = 1";

        $sqlLimit = "LIMIT $limitOffset,$limitLength";

        $sqlOrders = " ORDER BY s.sale_id DESC ";

        $query = "SELECT s.sale_id, s.user_id, u.user AS user_name, c.name AS client_name, s.budget_id, s.start_date, s.shipment_state_id, ship.title AS ship_name, s.payment_state_id, pay.title AS pay_name, s.total, s.description AS notes
            FROM sales AS s 
            LEFT JOIN users AS u ON s.user_id = u.user_id
            LEFT JOIN budget_versions AS v ON v.new_budget_id = s.budget_id 
            LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
            LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
            LEFT JOIN clients AS c ON s.client_id = c.client_id $sqlFilters $sqlOrders $sqlLimit"; 
        $this->db->query($query);
        $this->db->validateLastQuery();
        $ids = array();
        foreach($this->db->fetchAll() as $fila) {
            $ids[] = $fila['sale_id'];
        }
        return $ids;
    }
    public function getTotalOfSales($filters, $limitOffset, $limitLength) {
        $sqlFilters = "WHERE";
        $ids = $this->getSalesIds($filters, $limitOffset, $limitLength);
        if(!empty($ids)) 
            $sqlFilters .= " (sale_id = " . implode(" OR sale_id = ", $ids) . ") AND";
        else
            $sqlFilters .= " sale_id = 0 AND";
        $sqlFilters .= " active = 1"; 
        $query = "SELECT SUM(total) AS total 
            FROM sales AS s $sqlFilters 
            ORDER BY sale_id"; 
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch()['total'];
    }
    public function getSaleInfo($saleId) {
        $this->db->validateSanitizeId($saleId, "El número de venta es erróneo");
        $query = "SELECT s.sale_id, s.user_id, u.user AS user_name, c.client_id, c.name AS client_name, s.budget_id, v.budget_number, v.version AS budget_version, s.start_date, s.shipment_state_id, ship.title AS ship_name, s.payment_state_id, pay.title AS pay_name, s.description, s.subtotal, s.discount, s.tax, s.ship, sm.title AS ship_method_name, pm.title AS pay_method_name, s.total
                FROM sales AS s 
                LEFT JOIN users AS u ON s.user_id = u.user_id
                LEFT JOIN budget_versions AS v ON v.new_budget_id = s.budget_id 
                LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
                LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
                LEFT JOIN shipment_methods AS sm ON s.shipment_method_id = sm.shipment_method_id
                LEFT JOIN payment_methods AS pm ON s.payment_method_id = pm.payment_method_id
                LEFT JOIN clients AS c ON s.client_id = c.client_id 
                WHERE s.active = 1 AND s.sale_id = $saleId"; 
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch();
    }
    public function getSaleItems($saleId) { 
        $this->db->validateSanitizeId($saleId, "El número de venta es erróneo");
        $query = "SELECT si.product_id, p.description, si.sale_price, si.quantity, si.total_price, si.position 
                FROM sales_items AS si
                LEFT JOIN products AS p ON si.product_id = p.product_id 
                WHERE si.sale_id = $saleId";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetchAll(); 
    }
    public function getTotalRegisters($filters) { 
        $sqlFilters = "";
        // Validación de filtros e inclusión en query
        if(!empty($filters->client_id)) {
            $this->db->validateSanitizeString($filters->client_id, "El número del cliente es erróneo");
            if(!is_numeric($filters->client_id)) throw new Exception("El número del cliente es erróneo");
            $sqlFilters .= "WHERE c.client_id LIKE '%$filters->client_id%'";
        }
        if(!empty($filters->saleNumber)) {
            $this->db->validateSanitizeString($filters->saleNumber, "El número de la venta es erróneo");
            if(!is_numeric($filters->saleNumber)) throw new Exception("El número de la venta es erróneo");
            $sqlFilters .= "WHERE s.sale_id LIKE '%$filters->saleNumber%'";
        }
        if(!empty($filters->user)) {
            $this->db->validateSanitizeString(str: $filters->user, wildcards: true, errorMsg: "El filtro de usuario es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "u.user LIKE '%$filters->user%'";
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
            $sqlFilters .= "v.budget_number LIKE '%$filters->budget%'";
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
            $this->db->validateSanitizeId($filters->payment, "El filtro de pago es erróneo");
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= "WHERE ";
            $sqlFilters .= "s.payment_state_id = $filters->payment";
        }
        // Activo (alta/baja lógica)
        if(!empty($sqlFilters))
            $sqlFilters .= " AND ";
        else
            $sqlFilters .= "WHERE ";
        $sqlFilters .= "s.active = 1";

        $query = "SELECT COUNT(*) AS total_registers
            FROM sales AS s 
            LEFT JOIN users AS u ON s.user_id = u.user_id
            LEFT JOIN budget_versions AS v ON v.new_budget_id = s.budget_id 
            LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
            LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
            LEFT JOIN clients AS c ON s.client_id = c.client_id $sqlFilters";

        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch()['total_registers'];
    }
    private function getLastPayChangeNumber($id) {
        $queryLastChange = "SELECT MAX(change_number) AS last_change FROM sales_payment_states_changes 
                        WHERE active = 1 AND sale_id = $id";
        $this->db->query($queryLastChange);
        $this->db->validateLastQuery();
        if(!$this->db->numRows() || !$lastChange = $this->db->fetch()['last_change'])
            return false;
        else
            return $lastChange;
    }
    public function getLastPayState() {
        $query = "SELECT * FROM payment_states WHERE last_step = 1";
        $this->db->query($query);
        $this->db->validateLastQuery();

        if(!$this->db->numRows())
            return false;
        return $this->db->fetch();
    }
    private function getLastShipChangeNumber($id) {
        $queryLastChange = "SELECT MAX(change_number) AS last_change FROM sales_shipment_states_changes 
                        WHERE active = 1 AND sale_id = $id";
        $this->db->query($queryLastChange);
        $this->db->validateLastQuery();
        if(!$this->db->numRows() || !$lastChange = $this->db->fetch()['last_change'])
            return false;
        else
            return $lastChange;
    }
    public function getLastShipState() {
        $query = "SELECT * FROM shipment_states WHERE last_step = 1";
        $this->db->query($query);
        $this->db->validateLastQuery();

        if(!$this->db->numRows())
            return false;
        return $this->db->fetch();
    }
    public function getPaymentStateChanges($id) {
        $this->db->validateSanitizeId($id, "El identificador de la venta es inválido");
        $query = "SELECT c.sale_id, old.payment_state_id AS old_id, old.title AS old_title, 
            new.payment_state_id AS new_id, new.title AS new_title, u.user AS user_name, 
            c.change_number, c.date
                FROM sales_payment_states_changes AS c 
                LEFT JOIN payment_states AS old ON old.payment_state_id = c.old_state_id
                LEFT JOIN payment_states AS new ON new.payment_state_id = c.new_state_id 
                LEFT JOIN users AS u ON u.user_id = c.user_id
                WHERE c.active = 1 AND c.sale_id = $id
                ORDER BY change_number";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }
    public function getShipmentStateChanges($id) {
        $this->db->validateSanitizeId($id, "El identificador de la venta es inválido");
        $query = "SELECT c.sale_id, old.shipment_state_id AS old_id, old.title AS old_title, 
            new.shipment_state_id AS new_id, new.title AS new_title, u.user AS user_name, 
            c.change_number, c.date
                FROM sales_shipment_states_changes AS c 
                LEFT JOIN shipment_states AS old ON old.shipment_state_id = c.old_state_id
                LEFT JOIN shipment_states AS new ON new.shipment_state_id = c.new_state_id 
                LEFT JOIN users AS u ON u.user_id = c.user_id
                WHERE c.active = 1 AND c.sale_id = $id
                ORDER BY change_number";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }
    public function getClientSales($id) {
        $this->db->validateSanitizeId($id, "El identificador del cliente es inválido");
        $query = "SELECT s.sale_id, s.user_id, u.user AS user_name, c.name AS client_name, s.budget_id, v.budget_number, v.version AS budget_version, 
            s.start_date, s.shipment_state_id, ship.title AS ship_name, s.payment_state_id, nextShip.title AS next_shipment_state, 
            pay.title AS pay_name, nextPay.title AS next_payment_state, s.total, s.description AS notes
                FROM sales AS s 
            LEFT JOIN users AS u ON s.user_id = u.user_id
            LEFT JOIN budget_versions AS v ON v.new_budget_id = s.budget_id 
            LEFT JOIN shipment_states AS ship ON s.shipment_state_id = ship.shipment_state_id
            LEFT JOIN shipment_states AS nextShip ON ship.next_step = nextShip.shipment_state_id
            LEFT JOIN payment_states AS pay ON s.payment_state_id = pay.payment_state_id
            LEFT JOIN payment_states AS nextPay ON pay.next_step = nextPay.payment_state_id
            LEFT JOIN clients AS c ON s.client_id = c.client_id 
            WHERE c.client_id = $id ORDER BY s.start_date DESC";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }
    // Validaciones
    public function exist($id) {
        $this->db->validateSanitizeId($id, "El número de venta es erróneo");
        $query = "SELECT s.sale_id 
                FROM sales AS s
                WHERE s.sale_id = $id";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return (!empty($this->db->numRows()) ? $this->db->fetch()['sale_id'] : false);
    }
    // Altas, bajas y modificaciones
    /* Se arma función de nueva venta según formato de retorno de info. de la base datos */
    public function newSale2($info) {
        $query   = "";
        $columns = "";
        $values  = "";

        $columns .= "user_id";
        $values  .= $_SESSION['user_id'];

        if(!empty($info["budget_id"])) {
            $this->db->validateSanitizeId($info["budget_id"], "El identificador de la cotización es inválido");
            $columns .= ", budget_id";
            $values  .= ", " . $info["budget_id"];    
        }

        $this->db->validateSanitizeId($info["client_id"], "El identificador del cliente es inválido");
        $columns .= ", client_id";
        $values  .= ", " . $info["client_id"];

        $this->db->validateSanitizeFloat($info["subtotal"], "El precio subtotal de la venta es inválido");
        if($info["subtotal"] < 0)  
            throw new Exception("El precio subtotal de la venta no puede ser menor a 0");
        $columns .= ", subtotal";
        $values  .= ", " . $info["subtotal"];

        if(!empty($info["discount"])) {
            $this->db->validateSanitizeFloat($info["discount"], "El descuento de la venta es inválido");
            if($info["discount"] < 0)  
                throw new Exception("El descuento de la venta no puede ser menor a 0");
            $columns .= ", discount";
            $values  .= ", " . $info["discount"];
        }

        if(!empty($info["tax"])) {
            $this->db->validateSanitizeFloat($info["tax"], "Los recargos o impuestos de la venta son inválidos");
            if($info["tax"] < 0)  
                throw new Exception("Los recargos o impuestos de la venta no pueden ser menor a 0");
            $columns .= ", tax";
            $values  .= ", " . $info["tax"];
        }

        if(!empty($info["ship"])) {
            $this->db->validateSanitizeFloat($info["ship"], "El precio de envío de la venta es inválido");
            if($info["ship"] < 0)  
                throw new Exception("El precio de envío de la venta no pueden ser menor a 0");
            $columns .= ", ship";
            $values  .= ", " . $info["ship"];
        }

        $this->db->validateSanitizeFloat($info["total"], "El precio total de la venta es inválido");
        if($info["total"] < 0)  
            throw new Exception("El precio total de la venta no puede ser menor a 0");
        $columns .= ", total";
        $values  .= ", " . $info["total"];

        $this->db->validateSanitizeId($info["shipment_method_id"], "El medio de envío de la venta es inválido");
        $columns .= ", shipment_method_id";
        $values  .= ", " . $info["shipment_method_id"];

        $this->db->validateSanitizeId($info["payment_method_id"], "El medio de pago de la venta es inválido");
        $columns .= ", payment_method_id";
        $values  .= ", " . $info["payment_method_id"];

        if(!empty($info["description"])) {
            $this->db->validateSanitizeString(str: $info["description"], errorMsg: "Las notas son inválidas", maxLen: 350);
            $columns .= ", description";
            $values  .= ", '" . $info["description"] . "'";
        }

        $query = "INSERT INTO sales ($columns) VALUES ($values)";

        //QUERY INSERT
        $this->db->query($query); 
        $this->db->validateLastQuery();
        $lastSale = $this->db->getLastInsertId();
        if(!$lastSale)
            throw new Exception("Hubo un error al dar de alta la venta");

        return $lastSale;
    }
    public function newSale($sale) {
        $query   = "";
        $columns = "";
        $values  = "";

        $columns .= "user_id";
        $values  .= $_SESSION['user_id'];

        $this->db->validateSanitizeId($sale->client->client_id, "El identificador del cliente es inválido");
        $columns .= ", client_id";
        $values  .= ", " . $sale->client->client_id;

        $this->db->validateSanitizeFloat($sale->subtotalPrice, "El precio subtotal de la venta es inválido");
        if($sale->subtotalPrice < 0)  
            throw new Exception("El precio subtotal de la venta no puede ser menor a 0");
        $columns .= ", subtotal";
        $values  .= ", " . $sale->subtotalPrice;

        if(!empty($sale->discount)) {
            $this->db->validateSanitizeFloat($sale->discount, "El descuento de la venta es inválido");
            if($sale->discount < 0)  
                throw new Exception("El descuento de la venta no puede ser menor a 0");
            $columns .= ", discount";
            $values  .= ", " . $sale->discount;
        }

        if(!empty($sale->tax)) {
            $this->db->validateSanitizeFloat($sale->tax, "Los recargos o impuestos de la venta son inválidos");
            if($sale->tax < 0)  
                throw new Exception("Los recargos o impuestos de la venta no pueden ser menor a 0");
            $columns .= ", tax";
            $values  .= ", " . $sale->tax;
        }

        if(!empty($sale->ship)) {
            $this->db->validateSanitizeFloat($sale->ship, "El precio de envío de la venta es inválido");
            if($sale->ship < 0)  
                throw new Exception("El precio de envío de la venta no pueden ser menor a 0");
            $columns .= ", ship";
            $values  .= ", " . $sale->ship;
        }

        $this->db->validateSanitizeFloat($sale->totalPrice, "El precio total de la venta es inválido");
        if($sale->totalPrice < 0)  
            throw new Exception("El precio total de la venta no puede ser menor a 0");
        $columns .= ", total";
        $values  .= ", " . $sale->totalPrice;

        $this->db->validateSanitizeId($sale->shipMethod->shipment_method_id, "El medio de envío de la venta es inválido");
        $columns .= ", shipment_method_id";
        $values  .= ", " . $sale->shipMethod->shipment_method_id;

        $this->db->validateSanitizeId($sale->payMethod->payment_method_id, "El medio de pago de la venta es inválido");
        $columns .= ", payment_method_id";
        $values  .= ", " . $sale->payMethod->payment_method_id;

        if(!empty($sale->notes)) {
            $this->db->validateSanitizeString(str: $sale->notes, errorMsg: "Las notas son inválidas", maxLen: 350);
            $columns .= ", description";
            $values  .= ", '" . $sale->notes . "'";
        }

        $query = "INSERT INTO sales ($columns) VALUES ($values)";

        //QUERY INSERT
        $this->db->query($query); 
        $this->db->validateLastQuery();
        $lastSale = $this->db->getLastInsertId();
        if(!$lastSale)
            throw new Exception("Hubo un error al dar de alta la venta");
        return $lastSale;
    }
    /* Se arma función de nuevo ítem según formato de retorno de items de la base datos */
    public function newSaleItem2($item, $saleId) {
        $this->db->validateSanitizeId($saleId, "El identificador de la venta es inválido");

        $prodId = (int)trim($item["product_id"]);
        $this->db->validateSanitizeId($prodId, "El identificador del producto #$prodId es inválido");

        $salePrice = (float)trim($item["sale_price"]);
        $this->db->validateSanitizeFloat($salePrice, "El precio del producto #$prodId es inválido");
        if($salePrice < 0)  
            throw new Exception("El precio del producto #$prodId no puede ser menor a 0");

        $costPrice = (float)trim($item["cost_price"]);
        $this->db->validateSanitizeFloat($costPrice, "El costo del producto #$prodId es inválido");
        if($costPrice < 0)  
            throw new Exception("El costo del producto #$prodId no puede ser menor a 0");

        $quantity = (float)trim($item["quantity"]);
        $this->db->validateSanitizeFloat($quantity, "La cantidad del producto #$prodId es inválida");
        if($quantity <= 0)  
            throw new Exception("La cantidad del producto #$prodId no puede ser menor o igual a 0");

        $totalPrice = (float)$salePrice * $quantity;

        $totalCost = (float)$costPrice * $quantity;

        $position = (int)trim($item["position"]);
        $this->db->validateSanitizeInt($position, "La posición del producto #$prodId es inválida"); // cambiar por SanitizeId (las posiciones arrancan en 1)

        //QUERY INSERT
        $query = "INSERT INTO sales_items (sale_id, product_id, sale_price, cost_price, quantity, total_price, total_cost, position) 
                VALUES ($saleId, $prodId, $salePrice, $costPrice, $quantity,
                $totalPrice, $totalCost, $position)";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        $lastSaleItem = $this->db->getLastInsertId();
        if(!$lastSaleItem)
            throw new Exception("Hubo un error al dar de alta un ítem en la venta " . $saleId);
        return $lastSaleItem;
    }
    public function newSaleItem($saleItem, $saleId) {
        $this->db->validateSanitizeId($saleId, "El identificador de la venta es inválido");

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
        $query = "INSERT INTO sales_items (sale_id, product_id, sale_price, cost_price, quantity, total_price, total_cost, position) 
                VALUES ($saleId, $prodId, $salePrice, $costPrice, $quantity,
                $totalPrice, $totalCost, $position)";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        $lastSaleItem = $this->db->getLastInsertId();
        if(!$lastSaleItem)
            throw new Exception("Hubo un error al dar de alta un ítem en el presupuesto " . $saleId);
        return $lastSaleItem;
    }
    public function newSaleItems($items, $saleId) {
        $ret = array();
        foreach($items as $k => $item) {
            $item->position = $k;
            $ret[] = $this->newSaleItem($item, $saleId);
        }
        return $ret;
    }
    /* Se arma función de nuevos ítems según formato de retorno de items de la base datos */
    public function newSaleItems2($items, $saleId) {
        $ret = array();
        foreach($items as $k => $item) {
            $ret[] = $this->newSaleItem($item, $saleId); // Se usa newSaleItem porque viene formateado como object
        }
        return $ret;
    }
    /* Cambios de estado de las ventas */
    /* Cambios de estado de pago */
    private function nextPaymentState($id) {
        $this->db->validateSanitizeId($id, "El identificador de la venta es inválido");
        $queryStates = "SELECT s.payment_state_id, ps.next_step, ps.last_step, ps.title AS old_title, nps.title AS new_title
                    FROM sales AS s
                    LEFT JOIN payment_states AS ps ON s.payment_state_id = ps.payment_state_id
                    LEFT JOIN payment_states AS nps ON ps.next_step = nps.payment_state_id 
                    WHERE sale_id = $id";
        $this->db->query($queryStates);
        $this->db->validateLastQuery();
        $saleStates = $this->db->fetch();
        if($saleStates['last_step'] == 1 || empty($saleStates['next_step']))
            throw new Exception("El estado de pago es el último (no hay siguiente paso)");

        $lastChange = $this->getLastPayChangeNumber($id);
        if(!$lastChange)
            $lastChange = 1;
        else
            $lastChange++;

        $queryChange = "INSERT INTO sales_payment_states_changes (sale_id, old_state_id, new_state_id, change_number, user_id) 
                        VALUES ($id, " . $saleStates['payment_state_id'] . ", " . $saleStates['next_step'] . ", $lastChange, " . $_SESSION['user_id'] . ")";
        $this->db->query($queryChange);
        $this->db->validateLastQuery();

        $queryUpdate = "UPDATE sales SET payment_state_id = " . $saleStates['next_step'] . " WHERE sale_id = $id";
        $this->db->query($queryUpdate);
        $this->db->validateLastQuery();

        $change = new stdClass;
        $change->old_state      = $saleStates['old_title'];
        $change->new_state      = $saleStates['new_title'];
        $change->change_number  = $lastChange;
        return $change;
    }
    private function lastPaymentState($id) {
        $this->db->validateSanitizeId($id, "El identificador de la venta es inválido");
        $queryOldState = "SELECT s.payment_state_id, ps.last_step, ps.title
                    FROM sales AS s
                    LEFT JOIN payment_states AS ps ON s.payment_state_id = ps.payment_state_id 
                    WHERE sale_id = $id";
        $this->db->query($queryOldState);
        $this->db->validateLastQuery();
        $oldState = $this->db->fetch();
        if($oldState['last_step'] == 1)
            throw new Exception("El estado de pago es el último");

        $lastState = $this->getLastPayState();
        if(!$lastState)
            throw new Exception("Hubo un error consultando el estado de pago final");

        $lastChange = $this->getLastPayChangeNumber($id);
        if(!$lastChange)
            $lastChange = 1;
        else
            $lastChange++;
    
        $queryChange = "INSERT INTO sales_payment_states_changes (sale_id, old_state_id, new_state_id, change_number, user_id) 
                        VALUES ($id, " . $oldState['payment_state_id'] . ", " . $lastState['payment_state_id'] . ", $lastChange, " . $_SESSION['user_id'] . ")";
        $this->db->query($queryChange);
        $this->db->validateLastQuery();

        $queryUpdate = "UPDATE sales SET payment_state_id = " . $lastState['payment_state_id'] . " WHERE sale_id = $id";
        $this->db->query($queryUpdate);
        $this->db->validateLastQuery();

        $change = new stdClass;
        $change->old_state      = $oldState['title'];
        $change->new_state      = $lastState['title'];
        $change->change_number  = $lastChange;
        return $change;
    }
    private function nextShipmentState($id) {
        $this->db->validateSanitizeId($id, "El identificador de la venta es inválido");
        $queryStates = "SELECT s.shipment_state_id, ss.next_step, ss.last_step, ss.title AS old_title, nss.title AS new_title 
                    FROM sales AS s
                    LEFT JOIN shipment_states AS ss ON s.shipment_state_id = ss.shipment_state_id
                    LEFT JOIN shipment_states AS nss ON ss.next_step = nss.shipment_state_id 
                    WHERE sale_id = $id";
        $this->db->query($queryStates);
        $this->db->validateLastQuery();
        $saleStates = $this->db->fetch();
        if($saleStates['last_step'] == 1 || empty($saleStates['next_step']))
            throw new Exception("El estado de envío es el último (no hay siguiente paso)");

        $lastChange = $this->getLastShipChangeNumber($id);
        if(!$lastChange)
            $lastChange = 1;
        else
            $lastChange++;

        $queryChange = "INSERT INTO sales_shipment_states_changes (sale_id, old_state_id, new_state_id, change_number, user_id) 
                        VALUES ($id, " . $saleStates['shipment_state_id'] . ", " . $saleStates['next_step'] . ", $lastChange, " . $_SESSION['user_id'] . ")";
        $this->db->query($queryChange);
        $this->db->validateLastQuery();

        $queryUpdate = "UPDATE sales SET shipment_state_id = " . $saleStates['next_step'] . " WHERE sale_id = $id";
        $this->db->query($queryUpdate);
        $this->db->validateLastQuery();

        $change = new stdClass;
        $change->old_state      = $saleStates['old_title'];
        $change->new_state      = $saleStates['new_title'];
        $change->change_number  = $lastChange;
        return $change;
    }
    private function lastShipmentState($id) {
        $this->db->validateSanitizeId($id, "El identificador de la venta es inválido");
        $queryOldState = "SELECT s.shipment_state_id, ss.last_step, ss.title 
                    FROM sales AS s
                    LEFT JOIN shipment_states AS ss ON s.shipment_state_id = ss.shipment_state_id
                    WHERE sale_id = $id";
        $this->db->query($queryOldState);
        $this->db->validateLastQuery();
        $oldState = $this->db->fetch();
        if($oldState['last_step'] == 1)
            throw new Exception("El estado de envío es el último");

        $lastState = $this->getLastShipState();
        if(!$lastState)
            throw new Exception("Hubo un error consultando el estado de envío final");

        $lastChange = $this->getLastShipChangeNumber($id);
        if(!$lastChange)
            $lastChange = 1;
        else
            $lastChange++;
    
        $queryChange = "INSERT INTO sales_shipment_states_changes (sale_id, old_state_id, new_state_id, change_number, user_id) 
                        VALUES ($id, " . $oldState['shipment_state_id'] . ", " . $lastState['shipment_state_id'] . ", $lastChange, " . $_SESSION['user_id'] . ")";
        $this->db->query($queryChange);
        $this->db->validateLastQuery();

        $queryUpdate = "UPDATE sales SET shipment_state_id = " . $lastState['shipment_state_id'] . " WHERE sale_id = $id";
        $this->db->query($queryUpdate);
        $this->db->validateLastQuery();

        $change = new stdClass;
        $change->old_state      = $oldState['title'];
        $change->new_state      = $lastState['title'];
        $change->change_number  = $lastChange;
        return $change;
    }
    public function changeSaleState($action, $id) {
        $this->db->validateSanitizeString(str: $action, errorMsg: "La acción a realizar es errónea");
        $change = new stdClass;
        switch ($action) {
            case "nextPaymentState":
                $change = $this->nextPaymentState($id);
                $change->action = "pago";
                break;
            case "lastPaymentState":
                $change = $this->lastPaymentState($id);
                $change->action = "pago";
                break;
            case "nextShipmentState":
                $change = $this->nextShipmentState($id);
                $change->action = "envío";
                break;
            case "lastShipmentState":
                $change = $this->lastShipmentState($id);
                $change->action = "envío";
                break;
            default:
                throw new Exception("La acción provista no se reconoce");
        }
        return $change;
    }

    // Hasta acá
}

?>