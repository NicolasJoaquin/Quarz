<?php
// views/ViewStock.php

require_once '../fw/fw.php';
class ViewStock extends View {
    public $items;
    public function __construct(){
    }

    public function setProducts($i){
        $this->items = $i;
    }
}

?>