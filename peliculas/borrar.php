
        <?php
        require 'auxiliar.php';
        require '../auxiliar.php';

        encabezado('Confiración del Borrado');
        if (!comprobarLogueado()) {
            return;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? false;
        try {
            $error = [];
            comprobarParametro($id, $error);
            $pdo = conectar();
            $fila = buscarPelicula($pdo, $id, $error);
            comprobarErrores($error);
            ?>
            <h3>
                ¿Seguro que quieres borrar la película <?= $fila['titulo'] ?>?
            </h3>
            <form action="borrar.php?id=<?= $fila['id'] ?>" method="post">
                <input type="submit" value="Si" class="btn btn-success" />
                <a href="index.php"  class="btn btn-danger">No</a>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                borrarPelicula($pdo, $id, $error);
                $_SESSION['mensaje'] = "Película \"" . $fila['titulo']
                    .  "\" eliminada correctamente";
                header('Location: index.php');
            }
        } catch (Exception $e) {
            mostrarErrores($error);
        }
        ?>
    </body>
</html>
