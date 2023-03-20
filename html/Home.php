<?php 
// html/Home.php

?>
<div id="homeDiv">
    <div><!--Header--> 
        <h1>Bienvenido: <?= htmlentities($_SESSION['user']) ?> </h1>
        <h4>Su nivel de permisos: <?= $_SESSION['perm'] ?> </h4>
    </div>
</div>

    