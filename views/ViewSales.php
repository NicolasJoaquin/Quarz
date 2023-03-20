<?php
// views/ViewSales.php

require_once '../fw/fw.php';
class ViewSales extends View {
    public $sales;
    public function __construct(){
    }

    public function setSales($s){
        $this->sales = $s;
    }
}

?>