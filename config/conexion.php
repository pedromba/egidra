<?php

define("HOST","localhost");
define("USER","root");
define("PASSWORD","");
define("DB","egidra");

$conexion = new mysqli(HOST, USER, PASSWORD, DB);
if ($conexion->connect_error) {
    http_response_code(503);
    die('Error de conexión a la base de datos.');
}
$conexion->set_charset('utf8mb4');
