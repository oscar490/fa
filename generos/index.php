        <?php
        require '../auxiliar.php';

        const rutas = [
            "../peliculas/index.php"=>"Películas",
            "index.php"=>"Géneros",
            "../modificar_password.php"=>"Modificar constraseña",
        ];

        encabezado('Listado de Géneros', array_keys(rutas)[0]);
        navegacion(rutas);
        $genero = trim(filter_input(INPUT_GET, 'genero'));
        buscador('Género', 'genero', $genero);
            ?>

                <?php
                $pdo = conectar();
                $sent = $pdo->prepare("SELECT id, genero
                                         FROM generos
                                        WHERE lower(genero) LIKE lower(:genero)");
                $sent->execute([':genero' => "%$genero%"]);
                $cabeceras = [
                    'genero'=>"Género"
                ];
                mostrarTabla($cabeceras, $sent->fetchAll());
                ?>

            <div class="row">
                <div class="col-md-offset-5 col-md-4">
                    <a class="btn btn-default" href="insertar.php">Insertar una nuevo Género</a>
                </div>
            </div>
        </div>
        <br /><br />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
