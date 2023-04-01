<?php

// views/ViewProviders.php
require_once '../fw/fw.php';
class ViewProviders extends View {
    public $providers;
    public function __construct(){
    }

    public function setClients($p){
        $this->providers = $p;
    }
}

?>