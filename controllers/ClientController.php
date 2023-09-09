<?php
//controllers/ClientController.php
require_once '../fw/fw.php'; 
require_once '../models/Clients.php';
require_once '../views/FormNewClient.php';

class ClientController extends Controller {
    public function __construct() {
        /* Models */
        $this->models['clients'] = new Clients();
        /* Views */
        $this->views['formNew'] = new FormNewClient(title: "Nuevo cliente", includeJs: "js/newClient.js", includeCSS: "css/newClient.css", includesCSS: ["css/stdCustom.css"]);
        // $this->views['result'] = new CreateClientResult();
    }
    /* Validadores */
    private function validateClientNotEmpty() {
        if(empty($_POST['data'])) throw new Exception("Enviá un cliente para dar de alta");
        $data = json_decode($_POST['data']);
        if(empty($data->name)) throw new Exception("Enviá el nombre del cliente para darlo de alta");
        return $data;
    }
    /* Altas, bajas y modificaciones */
    public function newClient() {
        if(!$client = $this->validateClientNotEmpty()) throw new Exception("Enviá todos los datos obligatorios");
        $this->models['clients']->newClient($client);
        return true;
    }
    /* Views */
    public function viewForm() {
        $this->views['formNew']->render();
    }

    /* END: Nuevo */



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

}
?>