<?php
session_start();

require 'auxiliar.php';

if (!comprobarLogueado()) {
    return;
}

$_SESSION = [];
$params = session_get_cookie_params();
setcookie(
    session_name(),         // nombre
    '',                     // valor
    1,                      // tiempo de expiración (1970-01-01 00:00:01)
    $params['path'],        // ruta
    $params['domain'],      // dominio
    $params['secure'],      // secure
    $params['httponly']     // httponly
);
session_destroy();
header('Location: index.php');
