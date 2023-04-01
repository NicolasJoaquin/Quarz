<?php
// views/ViewProducts.php

require_once '../fw/fw.php';
class ViewProducts extends View {
    public $products;
    public function __construct(){
    }

    public function setProducts($p){
        $this->products = $p;
    }
}

?>