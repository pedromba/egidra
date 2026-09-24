<?php

define("HOST","localhost");
define("USER","egidra_user");
define("PASSWORD","Egidra1234");
define("DB","egidra");

$conexion = new mysqli(HOST, USER, PASSWORD, DB);
if ($conexion->connect_error) {
    http_response_code(503);
    die('Error de conexión a la base de datos.');
}
$conexion->set_charset('utf8mb4');
