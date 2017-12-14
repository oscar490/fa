<?php session_start() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <style type="text/css">
            .container {
                margin-top: 24px;
            }
        </style>
        <title>Login</title>
    </head>
    <body>
        <div class="container">
            <?php
            require 'auxiliar.php';
            require '../auxiliar.php';

            $usuario = trim(filter_input(INPUT_POST, 'usuario'));
            $password = trim(filter_input(INPUT_POST, 'password'));

            if (!empty($_POST)) {
                $error = [];
                try {
                    comprobarUsuario($usuario, $error);
                    comprobarPassword($password, $error);
                    comprobarErrores($error);
                    $fila = buscarUsuario($usuario, $password, $error);
                    $_SESSION['usuario'] = [
                        'id' => $fila['id'],
                        'nombre' => $fila['usuario'],
                    ];
                    header('Location: index.php');
                } catch (Exception $e) {
                    mostrarErrores($error);
                }
            }
            ?>
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="usuario">Usuario *</label>
                            <input type="text" class="form-control"
                                id="usuario"
                                name="usuario"
                                placeholder="Nombre de usuario"
                                value="<?= h($usuario) ?>" >
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <input type="password" class="form-control"
                                id="password"
                                name="password"
                                placeholder="Contraseña">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Recuérdame
                            </label>
                        </div>
                        <button type="submit" class="btn btn-default">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
