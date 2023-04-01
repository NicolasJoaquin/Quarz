<?php
// views/ViewProductsRestricted.php

require_once '../fw/fw.php';
class ViewProductsRestricted extends View {
    public $products;
    public function __construct(){
    }

    public function setProducts($p){
        $this->products = $p;
    }
}

?>