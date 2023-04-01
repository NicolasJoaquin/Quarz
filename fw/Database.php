<?php

// fw/Database.php
class Database {
    private $cn = false;
    private $res; // resultado de la ultima query ejecutada
    private $error; // string con mensaje de error de la última query ejecutada
    private $errorno; // código de error de la última llamada, devuelve cero si no ha ocurrido ningún error
    private static $instance = false;
    
    private function __construct(){

    }

    static public function getInstance(){  
        if(!self::$instance) self::$instance = new Database();
        return self::$instance;
    }
    
    private function connect(){
        $this->cn = mysqli_connect("localhost", "root", "", "quarz");
    }

    public function numRows(){
        return mysqli_num_rows($this->res);
    }

    public function query($q){
        if(!$this->cn) $this->connect();
        $this->res = mysqli_query($this->cn, $q); 
        return $this->res;
    }

    public function getError(){
        if(!$this->cn) $this->connect();
        $this->error = mysqli_error($this->cn); 
        return $this->error;
    }

    public function getErrorNo(){
        if(!$this->cn) $this->connect();
        $this->errorno = mysqli_errno($this->cn); 
        return $this->errorno;
    }

    public function fetch(){
        return mysqli_fetch_assoc($this->res);
    }

    public function fetchAll(){
        $ret = array();
        while($fila = $this->fetch()){
            $ret[] = $fila;
        }
        return $ret;
    }

    public function escape($str) {
        if(!$this->cn) $this->connect();
        return mysqli_escape_string($this->cn, $str);
    }

    public function escapeWildcards($str){
        $str = str_replace('%', '\%', $str);
        $str = str_replace('_', '\_', $str);
        return $str;
    }
}

?>