<?php
//models/PaymentMethods.php

require_once '../fw/fw.php';

class PaymentMethods extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM payment_methods"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }
}

?>