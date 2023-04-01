<!-- html/Login.php -->
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Login</title> 
        <meta http-equiv="content-type" content="text/html;charset=utf8"></meta>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">

    </head>
        <div class="container">
            <div class="row mt-5">
                <div class="col-12">
                  <div id="login-row" class="row justify-content-center align-items-center">
                      <div id="login-column" class="col-md-6">
                          <div id="login-box" class="col-md-12">
                              <form id="login-form" class="form" action="" method="POST">
                                  <?php
                                      if(isset($errorLogin)){
                                          echo $errorLogin;
                                      }
      
                                  ?>
                                <h3 class="text-center text-info ">Login</h3>
                                    <form id="login-form" class="form" action="../controllers/index.php" method="POST">
                                        <label for="user" class="text-info">Usuario:</label> <br>
                                        <input type="text" name="user" id="user" class="form-control"></br>
                                        <div class="form-group">
                                        <label for="password" class="text-info">Contraseña:</label> </br>
                                        <input type="password" name="pass" id="pass" class="form-control">
                                        </div>
                                        <input type="submit" name="btn" class="btn btn-info btn-md" value="Ingresar">
                                        <br>
                                        <a href="./newUser" class="stretched-link"> Crear nuevo usuario</a>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

    </body>
</html>