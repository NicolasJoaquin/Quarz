<?php
// models/Clients.php

require_once '../fw/fw.php';

class Clients extends Model {
    /* Last version */
    /* Getters */
    private function getAll() {
        $this->db->query("SELECT *
                            FROM clients");
        return $this->db->fetchAll();
    }
    /* Falta modi */
    public function getClients($filters, $orders, $limitOffset, $limitLength) {
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
        if(!empty($filters->number)) {
            $this->db->validateSanitizeString($filters->number, "El filtro del número de cliente es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.client_id LIKE '%$filters->number%'";
        }
        if(!empty($filters->name)) {
            $this->db->validateSanitizeString($filters->name, "El filtro del nombre es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.name LIKE '%$filters->name%'";
        }
        if(!empty($filters->cuit)) {
            $this->db->validateSanitizeString($filters->cuit, "El filtro del CUIT es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.cuit LIKE '%$filters->cuit%'";
        }
        if(!empty($filters->dni)) {
            $this->db->validateSanitizeString($filters->dni, "El filtro del DNI es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.dni LIKE '%$filters->dni%'";
        }
        if(!empty($filters->nickname)) {
            $this->db->validateSanitizeString($filters->nickname, "El filtro del apodo es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.nickname LIKE '%$filters->nickname%'";
        }
        if(!empty($filters->direction)) {
            $this->db->validateSanitizeString($filters->direction, "El filtro de la dirección es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.direction LIKE '%$filters->direction%'";
        }
        if(!empty($filters->email)) {
            $this->db->validateSanitizeString($filters->email, "El filtro del e-mail es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.email LIKE '%$filters->email%'";
        }
        if(!empty($filters->phone)) {
            $this->db->validateSanitizeString($filters->phone, "El filtro del teléfono es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.phone LIKE '%$filters->phone%'";
        }
        // Activo (alta/baja lógica)
        if(!empty($sqlFilters))
            $sqlFilters .= " AND ";
        else
            $sqlFilters .= " WHERE ";
        $sqlFilters .= "c.active = 1";

        $sqlOrders = "ORDER BY c.client_id DESC"; // Provisorio

        $sqlLimit = "LIMIT $limitOffset,$limitLength";

        $query = "SELECT c.*
                FROM clients AS c $sqlFilters $sqlOrders $sqlLimit";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        return $this->db->fetchAll();
    }
    public function getTotalRegisters($filters) {
        $sqlFilters = "";
        // Validación de filtros e inclusión en query
        if(!empty($filters->number)) {
            $this->db->validateSanitizeString($filters->number, "El filtro del número de cliente es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.client_id LIKE '%$filters->number%'";
        }
        if(!empty($filters->name)) {
            $this->db->validateSanitizeString($filters->name, "El filtro del nombre es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.name LIKE '%$filters->name%'";
        }
        if(!empty($filters->cuit)) {
            $this->db->validateSanitizeString($filters->cuit, "El filtro del CUIT es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.cuit LIKE '%$filters->cuit%'";
        }
        if(!empty($filters->dni)) {
            $this->db->validateSanitizeString($filters->dni, "El filtro del DNI es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.dni LIKE '%$filters->dni%'";
        }
        if(!empty($filters->nickname)) {
            $this->db->validateSanitizeString($filters->nickname, "El filtro del apodo es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.nickname LIKE '%$filters->nickname%'";
        }
        if(!empty($filters->direction)) {
            $this->db->validateSanitizeString($filters->direction, "El filtro de la dirección es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.direction LIKE '%$filters->direction%'";
        }
        if(!empty($filters->email)) {
            $this->db->validateSanitizeString($filters->email, "El filtro del e-mail es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.email LIKE '%$filters->email%'";
        }
        if(!empty($filters->phone)) {
            $this->db->validateSanitizeString($filters->phone, "El filtro del teléfono es erróneo", wildcards: true);
            if(!empty($sqlFilters))
                $sqlFilters .= " AND ";
            else
                $sqlFilters .= " WHERE ";
            $sqlFilters .= "c.phone LIKE '%$filters->phone%'";
        }
        // Activo (alta/baja lógica)
        if(!empty($sqlFilters))
            $sqlFilters .= " AND ";
        else
            $sqlFilters .= "WHERE ";
        $sqlFilters .= "c.active = 1";

        $query = "SELECT COUNT(*) AS total_registers
            FROM clients AS c $sqlFilters";
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch()['total_registers'];
    }

    /* Altas, bajas y modificaciones */
    public function newClient($data) { 
        $query   = "";
        $columns = "";
        $values  = "";

        $columns .= "user";
        $values  .= $_SESSION['user_id'];

        $this->db->validateSanitizeString($data->name, "El nombre del cliente es erróneo", 300, 5);
        $columns .= ", name";
        $values  .= ", '" . $data->name . "'";

        if(!empty($data->cuit)) {
            $this->db->validateSanitizeString($data->cuit, "El CUIT es erróneo", 11, 11);
            if(!is_numeric($data->cuit)) throw new Exception("El CUIT es erróneo");
            $columns .= ", cuit";
            $values  .= ", " . $data->cuit;
        }
        if(!empty($data->dni)) {
            $this->db->validateSanitizeString($data->dni, "El DNI es erróneo", 9, 7);
            if(!is_numeric($data->dni)) throw new Exception("El DNI es erróneo");
            $columns .= ", dni";
            $values  .= ", " . $data->dni;
        }
        if(!empty($data->nickname)) {
            $this->db->validateSanitizeString($data->nickname, "El apodo/nombre de fantasía es erróneo", 300, 3);
            $columns .= ", nickname";
            $values  .= ", '" . $data->nickname . "'";
        }
        if(!empty($data->direction)) {
            $this->db->validateSanitizeString($data->direction, "La dirección es errónea", 300, 5);
            $columns .= ", direction";
            $values  .= ", '" . $data->direction . "'";
        }
        if(!empty($data->email)) {
            $this->db->validateSanitizeString($data->email, "El e-mail es erróneo", 150, 10);
            $columns .= ", email ";
            $values  .= ", '" . $data->email . "'";
        }
        if(!empty($data->phone)) {
            $this->db->validateSanitizeString($data->phone, "El teléfono es erróneo", 25, 5);
            if(!is_numeric($data->phone)) throw new Exception("El teléfono es erróneo");
            $columns .= ", phone";
            $values  .= ", " . $data->phone;
        }

        //QUERY INSERT
        $query = "INSERT INTO clients ($columns) VALUES ($values)";
        $this->db->query($query); 
        $this->db->validateLastQuery();
        $lastClient = $this->db->getLastInsertId();
        if(!$lastClient)
            throw new Exception("Hubo un error al dar de alta el cliente");
        return $lastClient;
    }




    /* End: Last version */

}