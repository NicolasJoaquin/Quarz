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

    public function validateLastQuery() {
        $errno = $this->getErrorNo();
        if($errno !== 0) 
            throw new Exception($this->getError());
        else
            return true;
    }

    public function getLastInsertId() {
        if($this->cn->insert_id)
            return $this->cn->insert_id;
        else
            return false;
    }

    public function sanitizeInt($int) {
        $int = filter_var(trim($int), FILTER_SANITIZE_NUMBER_INT);
        $int = filter_var(trim($int), FILTER_VALIDATE_INT);
        return $int;
    }

    public function validateInt($int) {
        if(is_int($int))
            return true;
        return false;
    }

    public function validateSanitizeInt(&$int, $errorMsg = "El número es inválido") {
        $int = $this->sanitizeInt($int);
        if(!$this->validateInt($int))
            throw new Exception($errorMsg);
        return true;
    }

    public function validateId($id) {
        if(is_int($id) && $id > 0)
            return true;
        return false;
    }

    public function validateSanitizeId(&$id, $errorMsg = "El identificador enviado es inválido") {
        $id = $this->sanitizeInt($id);
        if(!$this->validateId($id))
            throw new Exception($errorMsg);
        return true;
    }

    public function sanitizeFloat($float) {
        $float = filter_var(trim($float), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $float = filter_var(trim($float), FILTER_VALIDATE_FLOAT);
        return $float;
    }

    public function validateFloat($float) {
        if(is_numeric($float))
            return true;
        return false;
    }

    public function validateSanitizeFloat(&$float, $errorMsg = "El número es inválido") {
        $float = $this->sanitizeFloat($float);
        if(!$this->validateFloat($float))
            throw new Exception($errorMsg);
        return true;
    }

    // Validación y sanitización de strings
    public function sanitizeString($str, $wildcards = false) {
        $str = htmlspecialchars(trim($str));
        $str = $this->escape($str);
        if($wildcards) {
            $str = $this->escapeWildcards($str);
        }
        return $str;
    }

    public function validateString($str, $maxLen = 300, $minLen = 0) {
        if(strlen($str) < $minLen) return false;
        if(strlen($str) > $maxLen) return false;
        return true;
    }

    public function validateSanitizeString(&$str, $errorMsg = "El texto es inválido", $maxLen = 100, $minLen = 0, $wildcards = false) {
        $str = $this->sanitizeString(str: $str, wildcards: $wildcards);
        if(!$this->validateString(str: $str, maxLen: $maxLen, minLen: $minLen))
            throw new Exception($errorMsg);
        return true;
    }

    // Validación y sanitización de fechas
    public function sanitizeDate($date, $format = "Y-m-d", $replaceSeparators = true, $separator = '-') { // Revisar seguridad !!
        if($replaceSeparators) {
            if(empty(trim($separator))) throw new Exception("Envíe un separador válido para sanitizar la fecha");
            $date = str_replace('/', $separator, $date); // Soporta fechas enviadas sólo con separadores '/'
        }
        $timestamp = strtotime($date);
        $sanitizedDate = date($format, $timestamp);
        
        return $sanitizedDate;
    }

    public function validateDate($date, $format = "Y-m-d") {
        $unixBaseDate = "";
        if($format == "Y-m-d")
            $unixBaseDate = "1970-01-01";
        if($format == "Y-m-d H:i:s") // Timestamp
            $unixBaseDate = "1970-01-01 00:00:00";
        if($date == $unixBaseDate || !$date)
            return false;
        return true;
    }
        
    public function validateSanitizeDate(&$date, $format = "Y-m-d", $errorMsg = "La fecha es inválida", $replaceSeparators = true, $separator = '-') {
        $date = $this->sanitizeDate($date, $format, $replaceSeparators, $separator);
        if(!$this->validateDate($date, $format))
            throw new Exception($errorMsg);
        return true;
    }

}

?>