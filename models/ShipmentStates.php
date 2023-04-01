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
}

?>