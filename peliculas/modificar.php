
        <?php
        require 'auxiliar.php';
        require '../auxiliar.php';

        encabezado('Modificar una película', '../index.php');

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
            extract($fila);
            if (!empty($_POST)):
                $titulo    = trim(filter_input(INPUT_POST, 'titulo'));
                $anyo      = trim(filter_input(INPUT_POST, 'anyo'));
                $sinopsis  = trim(filter_input(INPUT_POST, 'sinopsis'));
                $duracion  = trim(filter_input(INPUT_POST, 'duracion'));
                $genero_id = trim(filter_input(INPUT_POST, 'genero_id'));
                try {
                    $error = [];
                    comprobarTitulo($titulo, $error);
                    comprobarAnyo($anyo, $error);
                    comprobarDuracion($duracion, $error);
                    comprobarGenero($pdo, $genero_id, $error);
                    comprobarErrores($error);
                    $valores = compact(
                        'titulo',
                        'anyo',
                        'sinopsis',
                        'duracion',
                        'genero_id'
                    );
                    modificar($pdo, $id, $valores);
                    $_SESSION['mensaje'] = 'La película se ha modificado correctamente.';
                    header('Location: index.php');
                    return;
                } catch (Exception $e) {
                    mostrarErrores($error);
                }
            endif;
            formulario([
                ['titulo'=>$titulo],
                ['anyo'=>$anyo],
                ['duracion'=>$duracion],
                'sinopsis'=>$sinopsis,
                'genero_id'=>$genero_id
            ], $id);
        } catch (Exception $e) {
            mostrarErrores($error);
        }
        ?>
    </body>
</html>
