<?php
//controllers/PruebaController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../views/Prueba.php';

class PruebaController extends Controller{
    public function __construct(){
        // $this->models['providers'] = new Providers();
        $this->views['form'] = new Prueba(includeJs: "js/prueba.js", includesJs: array("js/prueba2.js", "js/prueba3.js"), includeCSS: "css/prueba.css" );
    }

    public function new($provider){
        $ret = true;
        try{
            $this->models['providers']->newProvider($provider); //VERIFICAR ESTO
        }
        catch(QueryErrorException $error){
            $ret = "Se produjo un error intentando dar de alta al proveedor " . $provider->name . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if($ret === true){
            $ret = "Se ha dado de alta con éxito el proveedor " . $provider->name;
        }
        return $ret;
    }

    public function delete($id){ //EN ESTE METODO FALTA IMPLEMENTAR EL BORRADO DE REGISTROS FORANEOS DE ESTE PROVEEDOR
        $ret = "Se ha eliminado con éxito el proveedor con id " . $id;
        try{
            $this->models['providers']->deleteProviderById($id);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando eliminar al proveedor con id " . $id . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }

        return $ret;
    }

    public function update($provider){
        $ret = true;
        try{
            $this->models['providers']->updateProvider($provider);
        }
        catch(QueryErrorException $error){ 
            $ret = "Se produjo un error intentando actualizar al cliente " . $provider->name . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        if($ret === true){
            $ret = "Se ha actualizado con éxito el proveedor " . $provider->name;
        }
        return $ret;
    }

    public function get($filterValue){
        try{
            $ret = $this->models['providers']->getProviders($filterValue);
        }
        catch(QueryErrorException $error){ //ACA HAY QUE VER COMO DEVOLVER Y MOSTRAR ESTE MSG DE ERROR (PROBABLEMENTE CONDICIONAL DESDE FRONT)
            $ret = "Se produjo un error intentando consultar los proveedores con el filtro " . $filterValue . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
        }
        return $ret;
    }

    public function viewCRUD($perm){
        if($perm == 1){
            $this->views['CRUD']->render();
        }else {
            echo "No tiene acceso a la visualización y modificación de proveedores";
            exit();
        }
    }

    public function viewForm($perm){
        if($perm == 1){
            $this->views['form']->render();
        }else {
            echo "No tiene acceso al alta de proveedores";
            exit();
        }
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


}
?>