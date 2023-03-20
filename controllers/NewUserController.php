<?php
//controllers/NewUserController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Users.php';
require_once '../views/FormNewUser.php';
require_once '../views/CreateUserResult.php';

//FIJARSE SI NO SE PUEDE HACER ABSTRACTA CON TODOS METODOS ESTATICOS
class NewUserController extends Controller{
    public function __construct(){
        $this->models['users'] = new Users();
        $this->views['form'] = new FormNewUser();
        $this->views['result'] = new CreateUserResult();
    }

    public function register($user){ 
        $msg = "Se ha dado de alta exitosamente al usuario " . $user->user . ".";
        try{
            $this->models['users']->newUser($user);
        }
        catch(QueryErrorException $error){ 
            $msg = "Se produjo un error intentando dar de alta al usuario " . $user->user . ",
            la base devuelve el siguiente error: " . $error->getErrorMsg();
            //VER ESTO
            return false;
        }
        return true;
        // $this->viewResult($msg);
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