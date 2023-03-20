<!-- html/Login.php -->
<form action="/quarz/home" method="POST" class="text-center w-200">
    <img class="mb-4" src="./extras/quarz.png" alt="Quarz Epoxi" width="170" height="170">
    <h1 class="h3 mb-3 fw-normal">Logueate</h1>

    <div class="form-floating">
        <input name="user" type="text" class="form-control" id="floatingInput">
        <label for="floatingInput">Usuario</label>
    </div>
    <div class="form-floating">
        <input name="pass" type="password" class="form-control" id="floatingPassword">
        <label for="floatingPassword">Contraseña</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>
    <a href="./newUser" class="w-100 btn btn-secondary">Nuevo usuario</a>
</form>

