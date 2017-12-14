            <?php
            require '../auxiliar.php';
            require 'auxiliar.php';

            const rutas = [
                "index.php"=>"Películas",
                "../generos/index.php"=>"Géneros",
                "../modificar_password.php"=>"Modificar contraseña",
            ];


            encabezado("Listado de Películas", array_keys(rutas)[0]);
            navegacion(rutas);

            $titulo = trim(filter_input(INPUT_GET, 'titulo'));
            buscador('Título','titulo', $titulo );
            ?>
            <div class="row">
                <?php
                $pdo = conectar();
                $clausulas = "FROM peliculas
                              JOIN generos ON genero_id = generos.id
                              WHERE lower(titulo) LIKE lower('%' || :titulo || '%')";
                $sent = $pdo->prepare("SELECT COUNT(*)
                                       $clausulas");

                $sent->execute([':titulo' => $titulo]);
                $numFilas = $sent->fetchColumn();
                $numPags = ceil($numFilas / FPP);
                $pag = filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT, [
                    'options' =>  [
                        'default'=> 1,
                        'min_range'=> 1,
                        'max_range'=> $numPags,
                    ]
                ]);



                $sent = $pdo->prepare("SELECT peliculas.id,
                                              titulo,
                                              anyo,
                                              left(sinopsis, 40) AS sinopsis,
                                              duracion,
                                              genero_id,
                                              genero
                                              $clausulas
                                     ORDER BY id
                                        LIMIT :limit
                                       OFFSET :offset");

                $sent->execute([
                    ':titulo' => $titulo,
                    ':limit' => FPP,
                    ':offset'=> ($pag - 1) * FPP,
                ]);

                $cabeceras = [
                    'id'=>"ID",
                    'titulo'=>"Título",
                    'anyo'=>"Año",
                    'sinopsis'=>"Sinopsis",
                    'duracion'=>"Duración",
                    'genero'=>"Género"
                ];
                mostrarTabla($cabeceras, $sent->fetchAll());
                paginador($pag, $numPags, $titulo);
                ?>



            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <a class="btn btn-default" href="insertar.php">Insertar una nueva película</a>
                </div>
            </div>
        </div>
        <?php
        pie();
