<?php
// exceptions/IsNotStringException.php

    class IsNotStringException extends Exception{
        private $errorMsg;

        public function __construct($errMsg){
            $this->errorMsg = $errMsg;
        }

        public function getErrorMsg(){
            return $this->errorMsg;
        }
    }

?>