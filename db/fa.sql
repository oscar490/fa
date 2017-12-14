
DROP TABLE IF EXISTS generos CASCADE;

CREATE TABLE generos
(
    id     BIGSERIAL    PRIMARY KEY
  , genero VARCHAR(255) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas
(
    id        BIGSERIAL    PRIMARY KEY
  , titulo    VARCHAR(255) NOT NULL
  , anyo      NUMERIC(4)
  , sinopsis  TEXT
  , duracion  SMALLINT     DEFAULT 0
                           CONSTRAINT ck_peliculas_duracion_positiva
                           CHECK (coalesce(duracion, 0) >= 0)
  , genero_id BIGINT       NOT NULL
                           REFERENCES generos (id)
                           ON DELETE NO ACTION
                           ON UPDATE CASCADE
);

-- INSERT

INSERT INTO generos (genero)
VALUES ('Comedia')
     , ('Terror')
     , ('Ciencia-Ficción')
     , ('Drama')
     , ('Aventuras');

INSERT INTO peliculas (titulo, anyo, sinopsis, duracion, genero_id)
VALUES ('Los últimos Jedi', 2017, 'Va uno y se cae...', 204, 3)
     , ('Los Goonies', 1985, 'Unos niños encuentran un tesoro', 120, 5)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1)
     , ('Aquí llega Condemor', 1996, 'Mejor no cuento nada...', 90, 1);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id       BIGSERIAL    PRIMARY KEY
  , usuario  VARCHAR(255) NOT NULL UNIQUE
                          CONSTRAINT ck_usuarios_usuario_sin_espacios
                          CHECK (position(' ' in usuario) = 0)
  , password VARCHAR(255) NOT NULL
);

INSERT INTO usuarios (usuario, password)
VALUES ('pepe', crypt('pepe', gen_salt('bf', 10)))
     , ('juan', crypt('juan', gen_salt('bf', 10)));
