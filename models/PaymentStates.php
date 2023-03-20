<?php
//models/PaymentStates.php

require_once '../fw/fw.php';

class PaymentStates extends Model{
    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //MODIFICAR LIMIT
        $this->db->query("SELECT *
                            FROM payment_states"); // MODIFICAR PARA VER VISTA
        return $this->db->fetchAll();
    }
}

?>