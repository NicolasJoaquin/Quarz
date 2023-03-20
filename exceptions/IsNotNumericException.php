<?php
// exceptions/IsNotNumericException.php

    class IsNotNumericException extends Exception{
        private $errorMsg;

        public function __construct($errMsg){
            $this->errorMsg = $errMsg;
        }

        public function getErrorMsg(){
            return $this->errorMsg;
        }
    }

?>