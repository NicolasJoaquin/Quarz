<?php
//controllers/ClientController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Clients.php';
require_once '../views/ViewClients.php';
// require_once '../views/CreateClientResult.php';

//POR AHORA ESTA CLASE SOLO MANEJA MODIFICACIÓN Y BAJA DE CLIENTES
class ClientController extends Controller{
    public function __construct(){
        $this->models['clients'] = new Clients();
        $this->views['CRUD'] = new ViewClients();
    }

    public function delete($id){
        $ret = true;
        try{
            $this->models['clients']->deleteClientById($id);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando dar de baja al cliente con id " . $id . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if($ret === true){
            $ret = "Se dió de baja con éxito el cliente con id " . $id;
        }
        return $ret;
    }

    public function update($client){
        $ret = true;
        try{
            $this->models['clients']->updateClient($client);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando actualizar al cliente " . $client->name . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if($ret === true){
            $ret = "Se actualizó con éxito el cliente con id " . $client->id;
        }
        return $ret;
    }

    public function get($filterValue){
        try{
            $ret = $this->models['clients']->getClients($filterValue);
        }
        catch(QueryErrorException $error){ //MODIFICAR ESTE CONTROL DE EXCEPCIONES
            $ret = "Se produjo un error intentando consultar los clientes con el filtro " . $filterValue . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        return $ret;
    }

    public function viewCRUD(){
        $this->views['CRUD']->render();
    }

    // TRASLADAR DESDE NewClientController
    // public function register($client){ 
    //     $msg = "Se ha dado de alta exitosamente al cliente " . $client->name . ".";
    //     try{
    //         $this->models['clients']->newClient($client);
    //     }
    //     catch(QueryErrorException $error){ 
    //         $msg = "Se produjo un error intentando dar de alta al cliente " . $client->name . ",
    //         la base devuelve el siguiente error: " . $error->getErrorMsg();
    //     }
    //     $this->viewResult($msg);
    // }

    // private function viewResult($resultMsg){
    //     $this->views['result']->msg = $resultMsg;
    //     $this->views['result']->render();
    // }

    // public function viewForm(){
    //     $this->views['form']->render();
    // }
}
?>