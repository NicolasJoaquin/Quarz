<?php
// fw/View.php
require_once '../fw/fw.php';        

abstract class View{
    protected $boostrap_includes_cdn = '
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    ';
    protected $includeJs    = "";
    protected $includesJs   = array();
    protected $includeCSS   = "";
    protected $includesCSS  = array();
    protected $includes     = "";
    protected $header       = "";
    protected $footer       = "";
    protected $title        = "";

    public function __construct($title = "Quarz", $includeJs = "", $includesJs = array(), $includeCSS = "", $includesCSS = array(), $header = "StdHeader", $footer = "StdFooter") {
        $this->includeJs    = $includeJs;
        $this->includesJs   = $includesJs;        
        $this->includeCSS   = $includeCSS;
        $this->includesCSS  = $includesCSS;
        $this->header       = $header;
        $this->footer       = $footer;
        $this->title        = $title;

        $this->setIncludes();
    }    

    private function setIncludes() {
        // Includes JS
        if(!empty($this->includeJs)) {
            $this->includes .= "<script src='".$this->includeJs."'></script>";
        }
        if(!empty($this->includesJs) && count($this->includesJs)) {
            foreach ($this->includesJs as $v) {
                $this->includes .= "<script src='".$v."'></script>";
            }
        }
        // Includes CSS
        if(!empty($this->includeCSS)) {
            $this->includes .= "<link rel='stylesheet' type='text/css' href='". $this->includeCSS ."' />";
        }
        if(!empty($this->includesCSS) && count($this->includesCSS)) {
            foreach ($this->includesCSS as $v) {
                $this->includes .= "<link rel='stylesheet' type='text/css' href='". $this->$v ."' />";
            }
        }
    }
    public function render() {
        include_once '../html/' . $this->header . '.php';
        include_once '../html/' . get_class($this) . '.php';
        include_once '../html/' . $this->footer . '.php';
    }
}

?>