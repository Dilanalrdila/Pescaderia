<?php
// conexion.php
$host = "localhost";
$user = "root";
$pass = "123";
$db   = "tienda_php";

$conexion = new mysqli($host, $user, $pass, $db);
if ($conexion->connect_error) {
    echo "Error de conexión: " . $conexion->connect_error;
    exit;
}
?>

