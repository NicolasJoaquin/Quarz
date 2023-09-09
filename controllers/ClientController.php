<?php
//controllers/ClientController.php
require_once '../fw/fw.php'; 
require_once '../models/Clients.php';
require_once '../models/Sales.php';
require_once '../models/Budgets.php';
require_once '../views/FormNewClient.php';
require_once '../views/ViewClients.php';
require_once '../views/ViewClient.php';
require_once '../views/ViewClientSales.php';
// require_once '../views/ViewClientBudgets.php';

class ClientController extends Controller {
    public function __construct() {
        /* Models */
        $this->models['clients'] = new Clients();
        $this->models['sales']   = new Sales();
        $this->models['budgets'] = new Budgets();
        /* Views */
        $this->views['formNew']       = new FormNewClient(title: "Nuevo cliente", includeJs: "js/newClient.js", includeCSS: "css/newClient.css", includesCSS: ["css/stdCustom.css"]);
        $this->views['dashboard']     = new ViewClients(title: "Dashboard clientes", includeJs: "js/viewClients.js", includeCSS: "css/viewClients.css", includesCSS: ["css/stdCustom.css"]);
        $this->views['clientDetail']  = new ViewClient(title: "Detalle del cliente", includeJs: "js/viewClient.js", includeCSS: "css/viewClient.css", includesCSS: ["css/stdCustom.css"]);
        $this->views['clientSales']   = new ViewClientSales(title: "Ventas del cliente", includeJs: "js/viewClientSales.js", includeCSS: "css/viewClientSales.css", includesCSS: ["css/stdCustom.css"]);
        // $this->views['clientBudgets'] = new ViewClientBudgets(title: "Cotizaciones del cliente", includeJs: "js/viewClientBudgets.js", includeCSS: "css/viewClientBudgets.css", includesCSS: ["css/stdCustom.css"]);
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
    public function viewDashboard() {
        $this->views['dashboard']->render();
    }
    public function viewClientDetail() { 
        if(empty($_GET['id'])) throw new Exception("El identificador del cliente a consultar está vacío o es inválido");
        $client = $this->models['clients']->getClientDetail($_GET['id']);
        $this->views['clientDetail']->client = $client;
        $this->views['clientDetail']->render();
    }
    public function viewClientSales() { 
        if(empty($_GET['id'])) throw new Exception("El identificador del cliente está vacío o es inválido");
        $sales  = $this->models['sales']->getClientSales($_GET['id']);
        $client = $this->models['clients']->getClientDetail($_GET['id']);
        /* Format de fecha para mostrar en el front */
        foreach($sales as $k => $sale) {
            if($k == -1)
                continue;
            $sales[$k]['start_date'] = $this->sqlDateToNormal($sale['start_date']);
        }
        $this->views['clientSales']->client = $client;
        $this->views['clientSales']->sales = $sales;
        $this->views['clientSales']->render();
    }
    public function viewClientBudgets() { 
        if(empty($_GET['id'])) throw new Exception("El identificador del cliente está vacío o es inválido");
        $budgets  = $this->models['budgets']->getClientBudgets($_GET['id']);
        $client = $this->models['clients']->getClientDetail($_GET['id']);
        /* Format de fecha para mostrar en el front */
        foreach($budgets as $k => $budget) {
            if($k == -1)
                continue;
            $budget[$k]['start_date'] = $this->sqlDateToNormal($budget['start_date']);
        }
        $this->views['clientBudgets']->client = $client;
        $this->views['clientBudgets']->budgets = $budgets;
        $this->views['clientBudgets']->render();
    }


    
    // viewClientBudgets
    /* Getters */
    public function getClientsToDashboard() { 
        $filters        = new stdClass();
        $orders         = new stdClass();
        $data           = new stdClass();
        $limitOffset    = 0;
        $limitLength    = 10000;
        if(!empty($_GET['filters']))
            $filters = json_decode($_GET['filters']);
        if(!empty($_GET['orders']))
            $orders = json_decode($_GET['orders']);
        if(!empty($_GET['limitOffset']))
            $limitOffset = json_decode($_GET['limitOffset']);
        if(!empty($_GET['limitLength']))
            $limitLength = json_decode($_GET['limitLength']);
        $data->clients   = $this->models['clients']->getClients($filters, $orders, $limitOffset, $limitLength);
        $data->registers = $this->models['clients']->getTotalRegisters($filters);
        return $data;
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