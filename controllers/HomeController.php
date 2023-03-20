<?php
//controllers/HomeController.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework
require_once '../models/Users.php';
require_once '../views/FormLogin.php';
require_once '../views/LoginResult.php';
require_once '../views/Home.php'; 
require_once '../views/HomeRestricted.php'; 

class HomeController extends Controller{
    public function __construct(){
        $this->models['users'] = new Users();
        $this->views['form'] = new FormLogin();
        $this->views['result'] = new LoginResult();
        $this->views['adminHome'] = new Home();
    }

    public function login($user, $pass){
        if($this->models['users']->cnValidate($user, $pass)){
            $userData = $this->models['users']->getUserData($user);

            $_SESSION['log'] = true; 
            $_SESSION['user_id'] = $userData['user_id']; 
            $_SESSION['user'] = $userData['user'];     
            $_SESSION['name'] = $userData['name'];
            $_SESSION['last_name'] = $userData['last_name']; 
            $_SESSION['nickname'] = $userData['nickname'];
            $_SESSION['perm'] = $userData['permission_id']; 
            $_SESSION['user_email'] = $userData['email']; 

            //$msg = "¡" . $_SESSION['user'] . ", te has logeado exitosamente!";
        }
        // $this->views['result']->msg = $msg;
        // $this->views['result']->render();
        header("Location: ./home");

    }

    public function viewFormLogin(){
        $this->views['form']->render();
    }

    public function viewHome(){ // Esta función maneja la vista que vé cada usuario
            $this->views['adminHome']->render();
    }
}

