<?php

require 'auxiliar.php';

encabezado('Modificar contraseña', '/peliculas/index.php');

if (!comprobarLogueado()) {
    return;
}

$password = filter_input(INPUT_POST, 'password');
$confirmacion = filter_input(INPUT_POST, 'confirmacion');
$error  = [];

if (!empty($_POST)) {
    try {
        comprobarPassword($password, $error);
        comprobarPassword($confirmacion, $error);
        comprobarIgualdad($password, $confirmacion, $error);
        comprobarErrores($error);
        modificarPassword($password, $_SESSION['usuario']['id']);
        $_SESSION['mensaje'] = 'Contrseña cambiada con éxito';
        header('Location: peliculas/index.php');
    } catch (Exception $e) {
        mostrarErrores($error);
    }
}

 ?>

 <form method="post" class="form-horizontal">
     <div class="form-group">
         <label for="inputEmail3" class="col-sm-4 control-label">
             Contraseña nueva:
         </label>
         <div class="col-sm-3">
             <input type="password" class="form-control" id="inputEmail3"
                name='password' />
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">
            Confirmación de Constraseña:
        </label>
        <div class="col-sm-3">
            <input type="password" class="form-control" id="inputEmail3"
                name='confirmacion' />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-2">
            <input type="submit" class="btn btn-success" id="inputEmail3"
                value='Cambiar' />
            <a href='peliculas/index.php' class="btn btn-danger">Cancelar</a>
        </div>
    </div>
 </form>
