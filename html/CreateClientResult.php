<?php 
// html/CreateClientResult.php
require_once '../views/StdHeader.php'; 
require_once '../views/StdFooter.php';

$header = new StdHeader("Resultado del Registro");
$header->render();
?>

<h1><?= $this->msg ?></h1>
<a href="./home" class="btn btn-success stretched-link">Ir al Inicio</a> <br><br>
                                
<?php
$footer = new StdFooter();
$footer->render();
?>
