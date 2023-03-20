<?php 
// html/LoginResult.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Resultado del login");
$header->render();
?>

<h1><?= $this->msg ?></h1>
<a href="./home">Ir al Inicio</a>

<?php
$footer = new StdFooter();
$footer->render();
?>
