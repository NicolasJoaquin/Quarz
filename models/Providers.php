<?php
// models/Providers.php

require_once '../fw/fw.php';

class Providers extends Model {

    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //AGREGAR LIMIT
        $this->db->query("SELECT *
                            FROM providers");
        return $this->db->fetchAll();
    }

    public function getProviders($filterValue){ //MODIFICAR / AGREGAR LIMIT
        // VALIDO FILTRO
        $filterValue = substr($filterValue, 0, 100);
        $filterValue = $this->db->escape($filterValue);
        $filterValue = $this->db->escapeWildcards($filterValue);

        $this->db->query("SELECT *
                            FROM providers as p 
                            WHERE p.name LIKE '%$filterValue%' OR 
                                    p.CUIT LIKE '%$filterValue%' OR
                                    p.nickname LIKE '%$filterValue%' OR
                                    p.direction LIKE '%$filterValue%' OR
                                    p.email LIKE '%$filterValue%' OR
                                    p.phone LIKE '%$filterValue%'"); // MODIFICAR LIMIT
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newProvider($provider){
        $query = "INSERT INTO providers (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$provider->name', '$provider->CUIT', '$provider->nickname', '$provider->direction', '$provider->email', '$provider->phone')";

        //Valido name
        if(strlen($provider->name) > 255) die ("error 1 newProvider/Providers (modelo)");
        if(strlen($provider->name) < 3) die ("error 2 newProvider/Providers (modelo)");  
        $provider->name = $this->db->escape($provider->name);

        //Valido CUIT 
        if(!empty($provider->CUIT)){
            if(strlen($provider->CUIT) != 11) die ("error 3 newProvider/Providers (modelo)");
            if(!ctype_digit($provider->CUIT)) die ("error 4 newProvider/Providers (modelo)");
        }else{
            $query = "INSERT INTO providers (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$provider->name', NULL, '$provider->nickname', '$provider->direction', '$provider->email', '$provider->phone')";
        }
        
        //Valido nickname
        if(!empty($provider->nickname)){
            if(strlen($provider->nickname) > 255 ) die ("error 5 newProvider/Providers (modelo)");
            if(strlen($provider->nickname) < 3 ) die ("error 6 newProvider/Providers (modelo)");  
            $provider->nickname = $this->db->escape($provider->nickname);    
        }
        
        //Valido direction
        if(!empty($provider->direction)){
            if(strlen($provider->direction) > 255 ) die ("error 7 newProvider/Providers (modelo)");
            if(strlen($provider->direction) < 3 ) die ("error 8 newProvider/Providers (modelo)"); 
            $provider->direction = $this->db->escape($provider->direction);    
        }

        //Valido email
        if(!empty($provider->email)){
            if(strlen($provider->email) > 255 ) die ("error 9 newProvider/Providers (modelo)");
            if(strlen($provider->email) < 8 ) die ("error 10 newProvider/Providers (modelo)");  
            $provider->email = $this->db->escape($provider->email);    
        }else {
            $query = "INSERT INTO providers (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$provider->name', $provider->CUIT, '$provider->nickname', '$provider->direction', NULL, '$provider->phone')";
        }

        //Valido phone
        if(!empty($provider->phone)){
            if(strlen($provider->phone) > 25) die ("error 11 newProvider/Providers (modelo)");
            if(!ctype_digit($provider->phone)) die ("error 12 newProvider/Providers (modelo)");     
        }

        if(empty($provider->CUIT) && empty($provider->email)){
            $query = "INSERT INTO providers (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$provider->name', NULL, '$provider->nickname', '$provider->direction', NULL, '$provider->phone')";
        }

        //QUERY INSERT
        $this->db->query($query);
        
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

    public function deleteProviderById($id){
        //Valido id
        if(!ctype_digit($id)) die ("error 1 deleteProviderById/Providers (modelo)");

        //QUERY DELETE
        $this->db->query("DELETE FROM providers
                            WHERE provider_id = $id");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

    public function updateProvider($provider){
        $query = "UPDATE providers
                    SET name = '$provider->name', CUIT = '$provider->CUIT', nickname = '$provider->nickname',
                    direction = '$provider->direction', email = '$provider->email', phone = '$provider->phone'
                    WHERE provider_id = $provider->id";

        //Valido id
        if(!ctype_digit($provider->id)) die ("error 0 updateProvider/Providers (modelo)");

        //Valido name
        if(strlen($provider->name) > 255) die ("error 1 updateProvider/Providers (modelo)");
        if(strlen($provider->name) < 3) die ("error 2 updateProvider/Providers (modelo)");  
        $provider->name = $this->db->escape($provider->name);


        //Valido CUIT 
        if(!empty($provider->CUIT)){
            if(strlen($provider->CUIT) != 11) die ("error 3 newProvider/Providers (modelo)");
            if(!ctype_digit($provider->CUIT)) die ("error 4 newProvider/Providers (modelo)");
        }else{
            $query = "UPDATE providers
                        SET name = '$provider->name', CUIT = NULL, nickname = '$provider->nickname',
                        direction = '$provider->direction', email = '$provider->email', phone = '$provider->phone'
                        WHERE provider_id = $provider->id";
        }
        
        //Valido nickname
        if(!empty($provider->nickname)){
            if(strlen($provider->nickname) > 255 ) die ("error 5 newProvider/Providers (modelo)");
            if(strlen($provider->nickname) < 3 ) die ("error 6 newProvider/Providers (modelo)");  
            $provider->nickname = $this->db->escape($provider->nickname);    
        }
        
        //Valido direction
        if(!empty($provider->direction)){
            if(strlen($provider->direction) > 255 ) die ("error 7 newProvider/Providers (modelo)");
            if(strlen($provider->direction) < 3 ) die ("error 8 newProvider/Providers (modelo)"); 
            $provider->direction = $this->db->escape($provider->direction);    
        }

        //Valido email
        if(!empty($provider->email)){
            if(strlen($provider->email) > 255 ) die ("error 9 newProvider/Providers (modelo)");
            if(strlen($provider->email) < 8 ) die ("error 10 newProvider/Providers (modelo)");  
            $provider->email = $this->db->escape($provider->email);    
        }else {
            $query = "UPDATE providers
                    SET name = '$provider->name', CUIT = '$provider->CUIT', nickname = '$provider->nickname',
                    direction = '$provider->direction', email = NULL, phone = '$provider->phone'
                    WHERE provider_id = $provider->id";
        }

        //Valido phone
        if(!empty($provider->phone)){
            if(strlen($provider->phone) > 25) die ("error 11 newProvider/Providers (modelo)");
            if(!ctype_digit($provider->phone)) die ("error 12 newProvider/Providers (modelo)");     
        }   

        if(empty($provider->CUIT) && empty($provider->email)){
            $query = "UPDATE providers
                        SET name = '$provider->name', CUIT = NULL, nickname = '$provider->nickname',
                        direction = '$provider->direction', email = NULL, phone = '$provider->phone'
                        WHERE provider_id = $provider->id";
        }

        //QUERY UPDATE
        $this->db->query($query);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }
}