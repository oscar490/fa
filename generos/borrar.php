
        <?php
        require 'auxiliar.php';
        require '../auxiliar.php';

        encabezado('Confirmación del Borrado', '../peliculas/index.php');

        if (!comprobarLogueado()) {
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? false;
        } else {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        }

        try {
            $error = [];
            comprobarParametro($id, $error);
            $pdo = conectar();
            $fila = buscarGenero($pdo, $id, $error);
            comprobarErrores($error);

            if (!empty($_POST)) {

                try {
                    borrarGenero($pdo, $id, $error);
                    comprobarErrores($error);
                    $_SESSION['mensaje'] = 'El Género se ha borrado con éxito';
                    header('Location: index.php');
                } catch (Exception $e) {
                    mostrarErrores($error);
                }

            }

            ?>
            <h3>
                ¿Seguro que quieres borrar el género <?= $fila['genero'] ?>?
            </h3>
            <form action="borrar.php?" method="post">
                <input type="submit" value="Si" class="btn btn-success" />
                <input type='hidden' name='id' value="<?= $fila['id'] ?>">
                <a href="index.php"  class="btn btn-danger">No</a>
            </form>
            <?php


        } catch (Exception $e) {
            mostrarErrores($error);
        }

        ?>
    </body>
</html>
