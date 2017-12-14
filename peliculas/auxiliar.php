<?php



define('FPP', 4);

/**
 * Busca una película a partir de su ID.
 * @param  PDO       $pdo   La conexión a la base de datos
 * @param  int       $id    El ID de la película
 * @param  array     $error El array de errores
 * @return array            La fila que contiene los datos de la película
 * @throws Exception        Si la película no existe
 */
function buscarPelicula(PDO $pdo, int $id, array &$error): array
{
    $sent = $pdo->prepare('SELECT *
                             FROM peliculas
                            WHERE id = :id');
    $sent->execute([':id' => $id]);
    $fila = $sent->fetch();
    if (empty($fila)) {
        $error[] = 'La película no existe';
        throw new Exception;
    }
    return $fila;
}

/**
 * Borra una película a partir de su ID.
 * @param  PDO   $pdo   La conexión a la base de datos
 * @param  int   $id    El ID de la película
 * @param  array $error Los mensajes de error
 */
function borrarPelicula(PDO $pdo, int $id, array &$error): void
{
    $sent = $pdo->prepare("DELETE FROM peliculas
                                 WHERE id = :id");
    $sent->execute([':id' => $id]);
    if ($sent->rowCount() !== 1) {
        $error[] = 'Ha ocurrido un error al eliminar la película';
    }
}

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





function comprobarTitulo(string $titulo, array &$error): void
{
    if ($titulo === '') {
        $error[] = "El título es obligatorio";
        return;
    }
    if (mb_strlen($titulo) > 255) {
        $error[] = "El título es demasiado largo";
    }
}

function comprobarAnyo(string $anyo, array &$error): void
{
    if ($anyo === '') {
        return;
    }
    $filtro = filter_var($anyo, FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 0,
            'max_range' => 9999,
        ],
    ]);
    if ($filtro === false) {
        $error[] = 'No es un año válido';
    }
}

function comprobarDuracion(string $duracion, array &$error): void
{
    if ($duracion === '') {
        return;
    }
    $filtro = filter_var($duracion, FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 0,
            'max_range' => 32767,
        ],
    ]);
    if ($filtro === false) {
        $error[] = 'No es una duración válida';
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


function insertar(PDO $pdo, array $valores): void
{
    $cols = array_keys($valores);
    $vals = array_fill(0, count($valores), '?');
    $sql = 'INSERT INTO peliculas (' . implode(', ', $cols) . ')'
                        . 'VALUES (' . implode(', ', $vals) . ')';
    $sent = $pdo->prepare($sql);
    $sent->execute(array_values($valores));
}

function comp($valor)
{
    return $valor !== '';
}

function modificar(PDO $pdo, int $id, array $valores): void
{
    $sets = [];
    foreach ($valores as $k => $v) {
        $sets[] = $v === '' ? "$k = NULL" : "$k = ?";
    }
    $set = implode(', ', $sets);
    $sql = "UPDATE peliculas
               SET $set
             WHERE id = ?";
    $exec = array_values(array_filter($valores, 'comp'));
    $exec[] = $id;
    $sent = $pdo->prepare($sql);
    $sent->execute($exec);
}


function paginador($pag, $numPags, $titulo)
{
    ?>
    <div class="text-center">

    <?php
    if ($pag > 1):
        $p = $pag - 1;
        $url = "index.php?pag=$p&titulo=$titulo"
        ?>
            <a href="<?= $url ?>">
                <span>&laquo;</span>
            </a>
        <?php
    else:
        ?>
        <span>&laquo;</span>
        <?php
    endif;

    for ($p = 1; $p <= $numPags; $p++):
        $url = "index.php?pag=$p&titulo=$titulo";

        if ($pag === $p):
            ?>
                <?= $p ?>
            <?php
        else:
        ?>
            <a href="<?= $url ?>"><?= $p ?></a>
        <?php
        endif;
    endfor;

    if ($pag < $numPags):
        $p = $pag + 1;
        $url = "index.php?pag=$p&titulo=$titulo";
        ?>
            <a href="<?= $url ?>">
                <span>&raquo;</span>
            </a>
        <?php
    else:
        ?>
            <span>&raquo;</span>
        <?php
    endif;
    ?>
    </div>
    <?php

}
