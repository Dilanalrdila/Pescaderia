<?php<?php$host="localhost";
$usuario="raíz";
$pass="123";
$db   ="tienda_php";
$conexión=nuevomysqli($host,$usuario,$pass,$db);
Revoca y cambia esta contraseña, ya que está comprometida.

si($conexión->connect_error) {
    eco "Error de: ".$conexión->error_de_conexión;
    salida;
}
?>
