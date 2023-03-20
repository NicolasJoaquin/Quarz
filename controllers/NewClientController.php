<?php
//controllers/NewClientController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Clients.php';
require_once '../views/FormNewClient.php';
require_once '../views/CreateClientResult.php';

//FIJARSE SI NO SE PUEDE HACER ABSTRACTA CON TODOS METODOS ESTATICOS
class NewClientController extends Controller{
    public function __construct(){
        $this->models['clients'] = new Clients();
        $this->views['form'] = new FormNewClient();
        $this->views['result'] = new CreateClientResult();
    }

    public function register($client){  //FALTA MODIFICAR TODO ESTE METODO
        $msg = "Se ha dado de alta exitosamente al cliente " . $client->name . ".";
        try{
            $this->models['clients']->newClient($client);
        }
        catch(QueryErrorException $error){ 
            $msg = "Se produjo un error intentando dar de alta al cliente " . $client->name . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        $this->viewResult($msg);
    }

    private function viewResult($resultMsg){
        $this->views['result']->msg = $resultMsg;
        $this->views['result']->render();
    }

    public function viewForm(){
        $this->views['form']->render();
    }
}
?>