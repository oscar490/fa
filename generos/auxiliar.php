<?php




/**
 * Comprueba si un parámetro es correcto.
 *
 * Un parámetro se considera correcto si ha superado los filtros de validación
 * de filter_input(). Si el parámetro no existe, entendemos que su valor
 * también es false, con lo cual sólo tenemos que comprobar si el valor no
 * es false.
 * @param  mixed     $param El parámetro a comprobar
 * @param  array     $error El array de errores
 * @throws Exception        Si el parámetro no es correcto
 */
function comprobarParametro($param, array &$error): void
{
    if ($param === false) {
        $error[] = 'Parámetro incorrecto';
        throw new Exception;
    }
}








function comprobarGenero(PDO $pdo, $genero_id, array &$error): void
{
    if ($genero_id === '') {
        $error[] = 'El género es obligatorio';
        return;
    }
    $filtro = filter_var($genero_id, FILTER_VALIDATE_INT);
    if ($filtro === false) {
        $error[] = 'El género debe ser un número entero';
        return;
    }
    $sent = $pdo->prepare('SELECT COUNT(*)
                             FROM generos
                            WHERE id = :genero_id');
    $sent->execute([':genero_id' => $genero_id]);
    if ($sent->fetchColumn() === 0) {
        $error[] = 'El género no existe';
    }
}





function modificarGenero($pdo, $id, $genero)
{
    $sent = $pdo->prepare("UPDATE generos
                              SET genero = :genero
                            WHERE id = :id");

    $sent->execute([":genero"=>$genero, ":id"=>$id]);
}

function formularioGenero(string $genero, ?int $id): void
{
    if ($id === null) {
        $destino = 'insertar.php';
        $boton = 'Insertar';
    } else {
        $destino = "modificar.php?id=$id";
        $boton = 'Modificar';
    }

    ?>
    <form action="<?= $destino ?>" method="post"
        class="form-horizontal">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">
                Nombre: *
            </label>
            <div class="col-sm-4" >
                <input id="inputEmail3" type="text" name="genero"
                    value="<?= h($genero) ?>" class="form-control"><br>
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












function comprobarNombreGenero($genero, &$error)
{
    if ($genero === '') {
        $error[] = "El nombre del género es obligatorio";
        return;
    }
    if (mb_strlen($genero) > 255) {
        $error[] = "El nombre del género es demasiado largo";
    }
}

function comprobarExistencia($pdo, $genero, &$error)
{

    $sent = $pdo->prepare("SELECT COUNT(*)
                             FROM generos
                            WHERE lower(genero) LIKE lower(:genero) ");

    $sent->execute([":genero"=>"%$genero%"]);

    if ($sent->fetchColumn() == 1) {
        $error[] = "El género $genero ya existe";
    }

}

function insertarGenero($pdo, $genero)
{
    $sent = $pdo->prepare("INSERT INTO generos (genero)
                                    VALUES (:genero)");

    $sent->execute([":genero"=>$genero]);

    return $sent->fetch();

}

function buscarGenero($pdo, $id, &$error)
{
    $sent = $pdo->prepare("SELECT *
                             FROM generos
                            WHERE id = :id");

    $sent->execute([":id"=>$id]);
    $fila = $sent->fetch();
    if (empty($fila)) {
        $error[] = 'El género no existe';
    }

    return $fila;
}

function comprobarReferencia($pdo, $id, &$error)
{
    $sent = $pdo->prepare("SELECT *
                      FROM peliculas
                     WHERE genero_id = :genero_id");

    $sent->execute([":genero_id"=> $id]);

    if ($sent->rowCount() === 1) {
        $error[] = 'Existe una película con ese género';
    }
}

function borrarGenero($pdo, $id, &$error)
{
    $sent = $pdo->prepare("DELETE FROM generos
                                 WHERE id = :id");


    $sent->execute([":id"=>$id]);

    if (empty($sent->fetchAll())) {
        $error[] = 'Existe una película con ese género';
    }
}
