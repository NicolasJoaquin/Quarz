<?php
// models/Clients.php

require_once '../fw/fw.php';

class Clients extends Model {

    //GETERS------------------------------------------------------------------------------------------------------------------------
    public function getAll(){ //AGREGAR LIMIT
        $this->db->query("SELECT *
                            FROM clients");
        return $this->db->fetchAll();
    }

    public function getClients($filterValue){ //MODIFICAR / AGREGAR LIMIT
        // VALIDO FILTRO
        $filterValue = substr($filterValue, 0, 100);
        $filterValue = $this->db->escape($filterValue);
        $filterValue = $this->db->escapeWildcards($filterValue);

        $this->db->query("SELECT *
                            FROM clients as c 
                            WHERE c.name LIKE '%$filterValue%' OR 
                                    c.CUIT LIKE '%$filterValue%' OR
                                    c.nickname LIKE '%$filterValue%' OR
                                    c.direction LIKE '%$filterValue%' OR
                                    c.email LIKE '%$filterValue%' OR
                                    c.phone LIKE '%$filterValue%'
                                    LIMIT 25"); // MODIFICAR LIMIT
        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());
        return $this->db->fetchAll();
    }

    //ALTAS, BAJAS Y MODIFICACIONES------------------------------------------------------------------------------------------------------------------
    public function newClient($client){
        $query = "INSERT INTO clients (name, CUIT, nickname, direction, email, phone) 
                    VALUES ('$client->name', '$client->CUIT', '$client->nickname', '$client->direction', '$client->email', '$client->phone')";

        //Valido name
        if(strlen($client->name) > 50) die ("error 1 newClient/Clients (modelo)");
        if(strlen($client->name) < 4) die ("error 2 newClient/Clients (modelo)");  
        $client->name = $this->db->escape($client->name);

        //Valido CUIT 
        if(!empty($client->CUIT)){
            if(strlen($client->CUIT) != 11) die ("error 3 newClient/Clients (modelo)");
            if(!ctype_digit($client->CUIT)) die ("error 4 newClient/Clients (modelo)");
        }else{
            $query = "INSERT INTO clients (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$client->name', NULL, '$client->nickname', '$client->direction', '$client->email', '$client->phone')";
        }
        
        //Valido nickname
        if(!empty($client->nickname)){
            if(strlen($client->nickname) > 50 ) die ("error 5 newClient/Clients (modelo)");
            if(strlen($client->nickname) < 3 ) die ("error 6 newClient/Clients (modelo)");  
            $client->nickname = $this->db->escape($client->nickname);    
        }
        
        //Valido direction
        if(!empty($client->direction)){
            if(strlen($client->direction) > 100 ) die ("error 7 newClient/Clients (modelo)");
            if(strlen($client->direction) < 4 ) die ("error 8 newClient/Clients (modelo)"); 
            $client->direction = $this->db->escape($client->direction);    
        }

        //Valido email
        if(!empty($client->email)){
            if(strlen($client->email) > 255 ) die ("error 9 newClient/Clients (modelo)");
            if(strlen($client->email) < 8 ) die ("error 10 newClient/Clients (modelo)");  
            $client->email = $this->db->escape($client->email);    
        }else{
            $query = "INSERT INTO clients (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$client->name', '$client->CUIT', '$client->nickname', '$client->direction', NULL, '$client->phone')";
        }

        //Valido phone (opcional, en la base es posible NULL)
        if(!empty($client->phone)){
            if(strlen($client->phone) > 25) die ("error 11 newClient/Clients (modelo)");
            if(!ctype_digit($client->phone)) die ("error 12 newClient/Clients (modelo)");     
        }

        if(empty($client->CUIT) && empty($client->email)){
            $query = "INSERT INTO clients (name, CUIT, nickname, direction, email, phone) 
                        VALUES ('$client->name', NULL, '$client->nickname', '$client->direction', NULL, '$client->phone')";
        }

        //QUERY INSERT
        $this->db->query($query);

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

    public function deleteClientById($id){
        //Borra el cliente con ID $id, retorna true si la consulta delete funciona
        //Valido id
        if(!ctype_digit($id)) die ("error 1 deleteClientById/Clients (modelo)");

        //QUERY DELETE
        $this->db->query("DELETE FROM clients 
                            WHERE client_id = $id");

        //VERIFICACIÓN DE LA QUERY Y RETORNO
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }

    public function updateClient($client){
        $query = "UPDATE clients
                    SET name = '$client->name', CUIT = '$client->CUIT', nickname = '$client->nickname',
                    direction = '$client->direction', email = '$client->email', phone = '$client->phone'
                    WHERE client_id = $client->id";

        //Valido id
        if(!ctype_digit($client->id)) die ("error 0 updateClient/Clients (modelo)");

        //Valido name
        if(strlen($client->name) > 50) die ("error 1 updateClient/Clients (modelo)");
        if(strlen($client->name) < 4) die ("error 2 updateClient/Clients (modelo)");  
        $client->name = $this->db->escape($client->name);

        //Valido CUIT 
        if(!empty($client->CUIT)){
            if(strlen($client->CUIT) != 11) die ("error 3 updateClient/Clients (modelo)");
            if(!ctype_digit($client->CUIT)) die ("error 4 updateClient/Clients (modelo)");
        }else{
            $query = "UPDATE clients
                        SET name = '$client->name', CUIT = NULL, nickname = '$client->nickname',
                        direction = '$client->direction', email = '$client->email', phone = '$client->phone'
                        WHERE client_id = $client->id";
        }

        //Valido nickname
        if(!empty($client->nickname)){
            if(strlen($client->nickname) > 50 ) die ("error 5 updateClient/Clients (modelo)");
            if(strlen($client->nickname) < 3 ) die ("error 6 updateClient/Clients (modelo)");  
            $client->nickname = $this->db->escape($client->nickname);
        }        

        //Valido direction
        if(!empty($client->direction)){
            if(strlen($client->direction) > 100 ) die ("error 7 updateClient/Clients (modelo)");
            if(strlen($client->direction) < 4 ) die ("error 8 updateClient/Clients (modelo)"); 
            $client->direction = $this->db->escape($client->direction);
        }

        //Valido email 
        if(!empty($client->email)){
            if(strlen($client->email) > 255 ) die ("error 9 updateClient/Clients (modelo)");
            //if(strlen($client->email) < 12 ) die ("error 10 updateClient/Clients (modelo)");  
            $client->email = $this->db->escape($client->email);
        }else{
            $query = "UPDATE clients
                        SET name = '$client->name', CUIT = '$client->CUIT', nickname = '$client->nickname',
                        direction = '$client->direction', email = NULL, phone = '$client->phone'
                        WHERE client_id = $client->id";
        }        

        //Valido phone
        if(!empty($client->phone)){
            if(strlen($client->phone) > 25) die ("error 11 updateClient/Clients (modelo)");
            if(!ctype_digit($client->phone)) die ("error 12 updateClient/Clients (modelo)"); 
        }        

        if(empty($client->CUIT) && empty($client->email)){
            $query = "UPDATE clients
                        SET name = '$client->name', CUIT = NULL, nickname = '$client->nickname',
                        direction = '$client->direction', email = NULL, phone = '$client->phone'
                        WHERE client_id = $client->id";
        }

        //QUERY UPDATE
        $this->db->query($query);
        $errno = $this->db->getErrorNo();
        if($errno !== 0) throw new QueryErrorException($this->db->getError());  
        return true;
    }
}