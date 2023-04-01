<?php
// views/ViewBuys.php

require_once '../fw/fw.php';
class ViewBuys extends View {
    public $buys;
    public function setBuys($b){
        $this->buys = $b;
    }
}

?>