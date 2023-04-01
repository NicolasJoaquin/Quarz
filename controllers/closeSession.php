<?php 
// controllers/closeSession.php

require_once '../fw/fw.php'; //archivo que tiene todos los includes y requires del framework

session_start();

if(!isset($_SESSION['log'])){     
    header("Location: ./home");
    exit();
}

unset($_SESSION['log']);
unset($_SESSION['user']);
unset($_SESSION['name']); 
unset($_SESSION['last_name']);
unset($_SESSION['nickname']);
unset($_SESSION['perm']);

header("Location: ./home");

?>
