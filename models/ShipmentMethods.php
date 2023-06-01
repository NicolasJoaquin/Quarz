<?php
//models/ShipmentMethods.php

require_once '../fw/fw.php';

class ShipmentMethods extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM shipment_methods"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }
}

?>