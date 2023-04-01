<?php
//models/Users.php

require_once '../fw/fw.php';
//require_once '../models/Permissions.php';

class Users extends Model{

    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){
        $this->db->query("SELECT *
                            FROM users");
        return $this->db->fetchAll();
    }

    public function getUserData($user){
        // Retorna un array con los datos del usuario provisto en el argumento
        if(strlen($user) > 50 ) die ("error 1 getUserData/Users (modelo)");  
        $user = $this->db->escape($user);

        $this->db->query("SELECT *
                            FROM users as u 
                            WHERE u.user = '$user'"); 

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return $this->db->fetch();
    }

    public function getName($user){
        //Retorna el nombre del usuario provisto en el argumento, retorna falso si no encuentra el usuario, retora NULL si el nombre es NULL
        if(strlen($user) > 50 ) die ("error 1 getName/Users (modelo)");  
        $user = $this->db->escape($user);
        $user = $this->db->escapeWildcards($user);

        $this->db->query("SELECT u.name
                            FROM users as u 
                            WHERE u.user = '$user'"); 
        
        if($this->db->numRows() != 1) return false; //No encontró el usuario
        $row = $this->db->fetch();
        if(is_null($row['name'])) return NULL; //El usuario no tiene un nombre cargado
        return $row['name']; //Retorno el nombre del usuario
    }

    public function getLastName($user){
        //Retorna el apellido del usuario provisto en el argumento, retorna falso si no encuentra el usuario, retora NULL si el apellido es NULL
        if(strlen($user) > 50 ) die ("error 1 getLastName/Users (modelo)");  
        $user = $this->db->escape($user);
        $user = $this->db->escapeWildcards($user);

        $this->db->query("SELECT u.last_name
                            FROM users as u 
                            WHERE u.user = '$user'"); 
        
        if($this->db->numRows() != 1) return false; //No encontró el usuario
        $row = $this->db->fetch();
        if(is_null($row['last_name'])) return NULL; //El usuario no tiene un apellido cargado
        return $row['last_name']; //Retorno el apellido del usuario
    }

    public function getNickname($user){
        //Retorna el nickname del usuario provisto en el argumento, retorna falso si no encuentra el usuario, retora NULL si el nickname es NULL
        if(strlen($user) > 50 ) die ("error 1 getNickname/Users (modelo)");  
        $user = $this->db->escape($user);
        $user = $this->db->escapeWildcards($user);

        $this->db->query("SELECT u.nickname
                            FROM users as u 
                            WHERE u.user = '$user'"); 
        
        if($this->db->numRows() != 1) return false; //No encontró el usuario
        $row = $this->db->fetch();
        if(is_null($row['nickname'])) return NULL; //El usuario no tiene un nickname cargado
        return $row['nickname']; //Retorno el nickname del usuario
    }

    public function getPerm($user){
        //Retorna el permiso del usuario (array con id y título) provisto en el argumento (campo user, no por id), retorna falso si no encuentra el usuario
        if(strlen($user) > 50 ) die ("error 1 getPerm/Users (modelo)");  
        $user = $this->db->escape($user);
        $user = $this->db->escapeWildcards($user);

        $this->db->query("SELECT u.permission_id , p.title
                            FROM users as u JOIN permissions as p on u.permission_id = p.permission_id
                            WHERE u.user = '$user'"); 
        
        if($this->db->numRows() != 1) die("EN REVISIONNNNNNNNNNNNNNNN");//return false; 
        $row = $this->db->fetch();
        return array('id' => $row['permission_id'], 'title' => $row['title']);
    }

    //VALIDADORES------------------------------------------------------------------------------------------------------------------
    public function cnValidate($user, $pass){
        //Valida la conexión, retorna verdadero si coincide usuario y pass, devuelve falso si no encuentra resultados
        //Ya validé si está seteado en el controlador
        //valido user
        if(strlen($user) > 50 ) die ("error 1 cnValidate/Users (modelo)");  
        $user = $this->db->escape($user);
        
        //valido pass
        if(strlen($pass) > 40 ) die ("error 2 cnValidate/Users (modelo)");
        $pass = $this->db->escape($pass);
        $pass = sha1($pass);

        $this->db->query("SELECT *
                            FROM users
                            WHERE user = '$user' AND pass = '$pass'");
                            //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  

        if($this->db->numRows() != 1) return false;
        
        return true;
    }

    public function userExists($user){
        //Valida que exista el usuario, retorna verdadero si existe, devuelve falso si no
        //Ya validé si está seteado en el controlador
        //valido user
        if(strlen($user) > 50 ) die ("error 1 userValidate/Users (modelo)");  
        $user = $this->db->escape($user);
        $user = $this->db->escapeWildcards($user);
        
        $this->db->query("SELECT *
                            FROM users
                            WHERE user = '$user'");
        if($this->db->numRows() != 1) return false;
        return true;
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newUser($user){
        //Crea un nuevo usuario, retorna el array de la fila del usuario creado si hay éxito, si el usuario ya existe retorna false
        //Valido user
        if(strlen($user->user) > 50 ) die ("error 1 newUser/Users (modelo)");
        if(strlen($user->user) < 4 ) die ("error 2 newUser/Users (modelo)");  
        $user->user = $this->db->escape($user->user);

        //Valido name
        if(!empty($user->name)){
            if(strlen($user->name) > 50 ) die ("error 3 newUser/Users (modelo)");
            if(strlen($user->name) < 4 ) die ("error 4 newUser/Users (modelo)");  
            $user->name = $this->db->escape($user->name);
        }

        //Valido last_name
        if(!empty($user->last_name)){
            if(strlen($user->last_name) > 50 ) die ("error 5 newUser/Users (modelo)");
            if(strlen($user->last_name) < 4 ) die ("error 6 newUser/Users (modelo)");  
            $user->last_name = $this->db->escape($user->last_name);
        }        

        //Valido nickname
        if(!empty($user->nickname)){
            if(strlen($user->nickname) > 50 ) die ("error 7 newUser/Users (modelo)");
            if(strlen($user->nickname) < 3 ) die ("error 8 newUser/Users (modelo)");  
            $user->nickname = $this->db->escape($user->nickname);
        }

        //Valido email
        if(!empty($user->email)){
            if(strlen($user->email) > 255 ) die ("error 7 newUser/Users (modelo)");
            if(strlen($user->email) < 3 ) die ("error 8 newUser/Users (modelo)");  
            $user->email = $this->db->escape($user->email);
        }

        //Valido pass
        if(strlen($user->pass) > 50 ) die ("error 9 newUser/Users (modelo)");
        if(strlen($user->pass) < 8 ) die ("error 10 newUser/Users (modelo)");  
        $user->pass = $this->db->escape($user->pass);
        $user->pass = sha1($user->pass); // CAMBIAR ENCRIPTACION

        //Valido perm_id  
        $user->perm_id = "2"; // ESTO QUEDA A REVISAR
        if(!empty($user->perm_id)){
            if(!ctype_digit($user->perm_id)) die ("error 11 newUser/Users (modelo)");
        }

        //QUERY INSERT
        $this->db->query("INSERT INTO users (user, pass, permission_id, name, last_name, nickname, email) 
                            VALUES ('$user->user', '$user->pass', '$user->perm_id', '$user->name', '$user->last_name', '$user->nickname', '$user->email')");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }
}

?>