
        <?php
        require 'auxiliar.php';
        require '../auxiliar.php';

        encabezado('Insertar un nuevo Género', '../peliculas/index.php');

        if (!comprobarLogueado()) {
            return;
        }

        $genero = trim(filter_input(INPUT_POST, 'genero'));


        // recogerParametros();
        $error = [];
        if (!empty($_POST)):

            try {
                comprobarNombreGenero($genero, $error);

                $pdo = conectar();
                comprobarExistencia($pdo, $genero, $error);
                comprobarErrores($error);

                $fila = insertarGenero($pdo, $genero);
                $_SESSION['mensaje'] = 'El género se ha insertado correctamente.';
                header('Location: index.php');
                return;

            } catch (Exception $e) {
                mostrarErrores($error);
            }
        endif;
        formularioGenero($genero, null);
        ?>

    </body>
</html>
