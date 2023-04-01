<?php

// views/ViewClients.php
require_once '../fw/fw.php';
class ViewClients extends View {
    public $clients;

    public function setClients($c){
        $this->clients = $c;
    }
}

?>