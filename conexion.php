<?php<?php
// conexión.php

$host = "localhost";
$usuario = "root"; // o el usuario correcto
$pass = "123";     // cámbiala por una contraseña segura
$db = "tienda_php";

// Crear conexión
$conexion = new mysqli($host, $usuario, $pass, $db);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "Conexión exitosa";
?>


