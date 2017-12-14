        <?php
        require '../auxiliar.php';
        require 'auxiliar.php';

        encabezado('Insertar una película','../index.php');

        if (!comprobarLogueado()) {
            return;
        }

        recogerParametros();
        $error = [];

        if (!empty($_POST)):

            try {
                comprobarTitulo($titulo, $error);
                comprobarAnyo($anyo, $error);
                comprobarDuracion($duracion, $error);
                $pdo = conectar();
                comprobarGenero($pdo, $genero_id, $error);
                comprobarErrores($error);
                $valores = array_filter(compact(
                    'titulo',
                    'anyo',
                    'sinopsis',
                    'duracion',
                    'genero_id'
                ), 'comp');
                insertar($pdo, $valores);
                $_SESSION['mensaje'] = 'La película se ha insertado correctamente.';
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
        ], null);
        pie();
        ?>
