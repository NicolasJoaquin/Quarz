<?php
//models/ShipmentStates.php

require_once '../fw/fw.php';

class ShipmentStates extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM shipment_states"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }
    public function getLastStep() {
        $query = "SELECT * FROM shipment_states WHERE last_step = 1"; 
        $this->db->query($query);
        $this->db->validateLastQuery();
        return $this->db->fetch();    
    }
}

?>