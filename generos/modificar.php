
        <?php
        require 'auxiliar.php';
        require '../auxiliar.php';

        encabezado('Modificación de un Género', '../peliculas/index.php');

        if (!comprobarLogueado()) {
            return;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? false;
        try {
            $error = [];
            comprobarParametro($id, $error);
            $pdo = conectar();
            $fila = buscarGenero($pdo, $id, $error);
            comprobarErrores($error);
            if (!empty($_POST)):
                $genero = trim(filter_input(INPUT_POST, 'genero'));

                try {
                    $error = [];
                    comprobarNombreGenero($genero, $error);
                    buscarGenero($pdo, $id, $error);
                    comprobarErrores($error);
                    modificarGenero($pdo, $id, $genero);
                    $_SESSION['mensaje'] = 'El género se ha modificado correctamente.';
                    header('Location: index.php');
                    return;
                } catch (Exception $e) {
                    mostrarErrores($error);
                }
            endif;

            formularioGenero($fila['genero'], $id);
        } catch (Exception $e) {
            mostrarErrores($error);
        }

        ?>
    </body>
</html>
