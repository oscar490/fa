<?php
//  Funciones comunes.


/**
 * Crea una conexión a la base de datos y la devuelve.
 * @return PDO          La instancia de la clase PDO que representa la conexión
 * @throws PDOException Si se produce algún error que impide la conexión
 */
function conectar(): PDO
{
    try {
        return new PDO('pgsql:host=localhost;dbname=fa', 'fa', 'fa');
    } catch (PDOException $e) {
        ?>
        <h1>Error catastrófico de base de datos: no se puede continuar</h1>
        <?php
        throw $e;
    }
}



/**
 * Escapa una cadena correctamente.
 * @param  string $cadena La cadena a escapar
 * @return string         La cadena escapada
 */
function h(?string $cadena): string
{
    return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);
}

/**
 * Muestra en pantalla los mensajes de error capturados
 * hasta el momento.
 * @param array $error Los mensajes capturados
 */
function mostrarErrores(array $error): void
{
    foreach ($error as $v) {
        ?>
        <div class="row">
            <div class="alert alert-danger alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?= h($v) ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Comprueba si existe algún error que se haya recogido.
 * @param array $error Los mensajes capturados
 */
function comprobarErrores(array $error): void
{
    if (!empty($error)) {
        throw new Exception;
    }
}

/**
 * Comprueba si los números de id son el mismo, en caso que si, devuelve
 * la cadena de selección por defecto de una lista desplegable.
 * @param  int $id_fila  Número ID de la fila resultante de la consulta.
 * @param  int $id_param Número ID de la fila de la que se desea realizar
 *                       una operación.
 * @return string           Cadea de selección.
 */
function seleccionar($id_fila, $id_param)
{
    return $id_fila === $id_param ? 'selected' : '';
}

/**
 * Muestra una lista desplegable con los Géneros disponibles.
 * @param  int $id  Número ID de la fila a la cual se desea realizar
 *                  una operación.
 */
function listaDesplegable($id): void
{
    $pdo = conectar();
    $sent = $pdo->query("SELECT *
                           FROM generos")->fetchAll();

    foreach ($sent as $fila):
        ?>
            <option value="<?= $fila['id'] ?>"
                <?= seleccionar($fila['id'], $id)?>>
                <?= $fila['genero'] ?>
            </option>
        <?php
    endforeach;
}

/**
 * Muestra un formulario.
 * @param array $datos Array de los datos.
 * @param ?int  $id    Número ID de la fila de la que se va ha realizar
 *                     una operación.
 */
function formulario(array $datos, ?int $id): void
{
    if ($id === null) {
        $destino = 'insertar.php';
        $boton = 'Insertar';
    } else {
        $destino = "modificar.php?id=$id";
        $boton = 'Modificar';
    }
    extract($datos);
    $campos = [
        ['titulo'=>'Título*:'],
        ['anyo'=>'Año'],
        ['duracion'=>'Duración:'],
    ]

    ?>
    <form action="<?= $destino ?>" method="post" class="form-horizontal">
        <?php for ($i = 0; $i < count($campos); $i++): ?>
            <?php foreach ($campos[$i] as $k => $v): ?>
            <div class='form-group'>
                <label for="inputEmail3" class="col-sm-4 control-label">
                    <?= h($v) ?>
                </label>
                <div class="col-sm-4">
                <input id="inputEmail3" type="text" name="<?= $k ?>"
                    value="<?= h($datos[$i][$k]) ?>" class="form-control" ><br>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endfor?>

        <div class='form-group'>
            <label for="inputEmail3" class="col-sm-4 control-label">
                Sinopsis:
            </label><br />
            <div class="col-sm-4">
                <textarea
                    id="inputEmail3"
                    name="sinopsis"
                    class="form-control"
                    rows="8"
                    cols="70"><?= h($sinopsis) ?></textarea><br>
            </div>
        </div>
        <div class='form-group'>
            <label for="inputEmail3" class="col-sm-4 control-label">
                Género*:
            </label>
            <div class="col-sm-4">
                <select name='genero_id' class="form-control">
                    <?php listaDesplegable($genero_id) ?>
                </select><br />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <input type="submit" value="<?= $boton ?>"
                    class="btn btn-success">
                <a href="index.php" class="btn btn-danger">Cancelar</a>
            </div>
        </div>
    </form>
    <?php
}

/**
 * Recoge los parámetros de entrada por el método POST
 */
function recogerParametros(): void
{
    global $titulo, $anyo, $sinopsis, $duracion, $genero_id;

    $titulo    = trim(filter_input(INPUT_POST, 'titulo'));
    $anyo      = trim(filter_input(INPUT_POST, 'anyo'));
    $sinopsis  = trim(filter_input(INPUT_POST, 'sinopsis'));
    $duracion  = trim(filter_input(INPUT_POST, 'duracion'));
    $genero_id = trim(filter_input(INPUT_POST, 'genero_id'));
}

/**
 * Comprueba si se ha escrito correctamente el nombre de usuario en el
 * inicio de sesión.
 * @param string $usuario Nombre de usuario
 * @param array  $error   Array con los errores recogidos.
 */
function comprobarUsuario(string $usuario, array &$error): void
{
    if ($usuario === '') {
        $error[] = 'El usuario es obligatorio';
        return;
    }
    if (mb_strlen($usuario) > 255) {
        $error[] = 'El usuario es demasiado largo';
    }
    if (mb_strpos($usuario, ' ') !== false) {
        $error[] = 'El usuario no puede contener espacios';
    }
}

/**
 * Comprueba si se ha escrito correctamente la constraseña de inicio
 * de sesión.
 * @param string $password La contraseña de inicio de sesión.
 * @param array  $error    Array con los errores recogidos.
 */
function comprobarPassword(string $password, array &$error): void
{
    if ($password === '') {
        $error[] = 'La contraseña es obligatoria';

    } else if (mb_substr($password, 0, 1) === ' ') {
        $error[] = 'La constraseña no puede contener espacios';

    } else if (mb_strlen($password) > 255) {
        $error[] = 'La contraseña no puede ser tan larga';
    }
}

/**
 * Se comprueba si el usuario, escrito en el inicio de sesión, existe.
 * @param  string $usuario  Nombre de usuario.
 * @param  string $password Contrseña.
 * @param  array  $error    Array de los errores recogidos.
 * @return array            Array con los datos del usuario logueado.
 * @throws Exception        Si el usuario no existe o la contraseña no coincide
 *                          con la original.
 */
function buscarUsuario(
    string $usuario,
    string $password,
    array &$error
): array
{
    $pdo = conectar();
    $sent = $pdo->prepare('SELECT *
                             FROM usuarios
                            WHERE usuario = :usuario');
    $sent->execute([':usuario' => $usuario]);
    $fila = $sent->fetch();

    if (empty($fila)) {
        $error[] = 'El usuario no existe';
        throw new Exception;
    }
    if (!password_verify($password, $fila['password'])) {
        $error[] = 'La contraseña no coincide';
        throw new Exception;
    }
    return $fila;
}

/**
 * Se comprueba si el usuario está logueado o no.
 * @return bool true en caso que el usuario este logueado.
 *              false en caso de que el usuario no esté logueado.
 */
function comprobarLogueado(): bool
{
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['mensaje'] = 'Usuario no identificado';
        header('Location: index.php');
        return false;
    }

    return true;
}

/**
 * Se muestra el encabezado de la página.
 * @param string $titulo Título de la página.
 */
function encabezado(string $titulo = '', $ruta): void
{
    session_start()
    ?>
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
                fieldset {
                    margin-bottom: 24px;
                }
                #buscar {
                    margin-bottom: 12px;
                }
            </style>
            <title><?= $titulo ?></title>
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="pull-right">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <h3>
                                <span class="label label-primary">
                                    <?= $_SESSION['usuario']['nombre'] ?>
                                </span>
                            </h3>
                            <a class="btn btn-info" href="logout.php">
                                <span class="glyphicon glyphicon-off" aria-hidden="true">
                            </a>
                        <?php else: ?>
                            <a class="btn btn-info" href="login.php">
                                <span class="glyphicon glyphicon-user" aria-hidden="true">
                            </a>
                        <?php endif ?>
                    </div>
                    <div class="pull-left">
                        <a href="<?= $ruta ?>">
                            <img src='../imágenes/logo.png'
                                width='100' height="100" /></a>
                        <br /><br />
                    </div>
                </div>
                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="row">
                        <div class="alert alert-success alert-dismissible" role="alert">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <?= $_SESSION['mensaje'] ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['mensaje']) ?>
                <?php endif;
}


/**
 * Se comprueba si la contraseña nomral y la de confirmación son iguales.
 * @param string $password     Contraseña nueva.
 * @param string $confirmacion Constraseña de confirmación.
 * @param array  $error        Array con los errores recogidos
 */
function comprobarIgualdad(string $password, string $confirmacion, array &$error): void
{
    if ($password !== $confirmacion) {
        $error[] = 'Las constraseñas no coinciden';
    }
}

/**
 * [modificarPassword description]
 * @param  string $password Constraseña nueva
 * @param  [type] $id       ID del usuario cual se va a cambiar su contraseña.
 */
function modificarPassword(string $password, int $id): void
{
    $pdo = conectar();
    $sent = $pdo->prepare("UPDATE usuarios
                              SET password = :password
                            WHERE id = :id");
    $sent->execute([
        ":password"=>password_hash($password, PASSWORD_DEFAULT),
        ":id"=>$id,
    ]);
}

/**
 * Devuelve la activación de la opción cuando se selecciona en la barra
 * de navegación
 * @param  string $ruta Cadena que contiene la ruta
 * @return string       Cadena que indica la activación.
 */
function comprobarActivado(string $ruta): string
{
    return $ruta === 'index.php' ? "class='active'" : '';
}

/**
 * Se muestra la barra de navegación
 * @param  array  $opcionesNav Array que contiene las opciones de
 *                             navegación.
 */
function navegacion(array $opcionesNav): void
{
    ?>
    <ul class="nav nav-pills">
    <?php
    foreach($opcionesNav as $k=>$v):
        ?>
        <li role='presentation'
            <?= comprobarActivado($k) ?>>
            <a href="<?= h($k) ?>">
                <?= h($v) ?>
            </a>
        </li>
        <?php
    endforeach;
    ?>
    </ul>
    <?php

}

/**
 * Se muestra un buscador interno del sitio.
 * @param  string $titulo Titulo que se muestra en el buscador.
 * @param  string $nombre Nombre de parámetro en input.
 * @param  string $valor  Valor buscado.
 */
function buscador(string $titulo, string $nombre, string $valor): void
{

    ?>
    <div class="row">
        <hr>
        <div class="panel panel-default">
            <div class="panel-heading">Buscar</div>
            <div class="panel-body">
                <form action="index.php" method="get" class="form-inline">
                    <div class="form-group">
                        <label for="titulo"><?= h($titulo) ?></label>
                        <input id="titulo" class="form-control" type="text"
                            name="<?= $nombre ?>" value="<?= h($valor) ?>">
                    </div>
                    <input type="submit" class="btn btn-default" value="Buscar">
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Se muestra la tabla de los resultados buscados.
 * @param  array  $cabeceras Array con los nombres de las cabeceras.
 * @param  array  $sentencia Array con los valores a mostrar en la tabla.
 */
function mostrarTabla(array $cabeceras, array $sentencia): void
{
    ?>
    <div class="row">
        <div class="col-md-offset-2  col-md-8">
            <table id="tabla" class="table table-striped"   >
                <thead>
                    <?php foreach ($cabeceras as $valores): ?>
                        <th>
                            <?= $valores ?>
                        </th>
                    <?php endforeach ?>
                        <th colspan="2">Operaciones</th>
                </thead>
                <tbody>
                    <?php foreach ($sentencia as $fila): ?>
                        <tr>
                            <?php foreach ($cabeceras as $k=>$v): ?>
                                <td><?= h($fila[$k]) ?></td>
                            <?php endforeach ?>
                            <td>
                                <a class="btn btn-info btn-xs" href="modificar.php?id=<?= h($fila['id']) ?>">
                                    Modificar
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-danger btn-xs" href="borrar.php?id=<?= h($fila['id']) ?>">
                                    Borrar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
/**
 * Muestra el pié de página.
 */
function pie(): void
{
    ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <br /><br /><br />
        </body>
    </html>
    <?php
}
