<?php 
// html/FormNewUser.php
?>
 
<div>
    <h1>Ingrese los datos para crear un nuevo usuario en el sistema</h1>
</div>

<form method="POST">
    <label for="user">Ingrese el nombre de usuario a crear: </label>
    <input type="text" name="user" id="user" required> <br>

    <label for="name">Ingrese su nombre: </label>
    <input type="text" name="name" id="name"> <br>

    <label for="last_name">Ingrese su apellido: </label>
    <input type="text" name="last_name" id="last_name"> <br>

    <label for="nickname">Ingrese su apodo/nickname: </label>
    <input type="text" name="nickname" id="nickname"> <br>

    <label for="email">Ingrese su email: </label>
    <input type="email" name="email" id="email"> <br>

    <label for="pass">Ingrese contraseña: </label>
    <input type="text" name="pass" id="pass" required><br>

    <label for="pass_validation">Confirme su contraseña: </label>
    <input type="text" name="pass_validation" id="pass_validation" required><br>

    <input type="button" name="submit" id="submit" value="Crear">
</form>
<!-- <a href="./home">Volver al Inicio</a> -->

<script>
    $(document).ready(function (){
        function locate(url){
            $(location).attr('href',url);
        }

        function getUserData(){
            var userData = {user : $("#user").val(), name : $("#name").val(), last_name : $("#last_name").val(), nickname : $("#nickname").val(),
                pass : $("#pass").val(), pass_validation : $("#pass_validation").val(), email : $("#email").val()};
            return userData;
        }
        
        function validateForm(){
            if($("#user").val().length < 4){ 
                alert("El nombre de usuario debe tener más de 4 caracteres");
                return false;
            }
            
            if($("#pass").val().length < 8){
                alert("La contraseña debe tener más de 8 caracteres");
                return false;
            }
            if($("#pass_validation").val() !== $("#pass").val()){
                alert("Valide correctamente su contraseña");
                return false;
            } 
            return true;
        }

        $("#submit").click(function(){
            if(validateForm()){
                var userData = getUserData();
                userData = JSON.stringify(userData);
                $.post("./newUser", {userData: userData}, function(response){
                    // VER QUE PONGO ACÁ
                    alert(response);
                    console.log(response);
                    locate("");
                });
            }
        });
    });
</script>

