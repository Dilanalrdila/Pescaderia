<?php 
session_start();
if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
include "conexion.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? '0';
    $peso = $_POST['peso'] ?? '';
    $id_categoria = $_POST['id_categoria'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';
    $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, peso, id_categoria, descripcion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $d, $precio, $peso, $id_categoria, $descripcion);
    $stmt->execute();
    $stmt->close();
    header("Location: productos.php");
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Agregar producto</title>
<link rel="stylesheet" href="css/estilo.css">
</head>
<body class="body-bg">
<div class="box">
<h2>Agregar producto</h2>
<form method="post" action="">
    <input name="nombre" placeholder="Nombre" required><br>
    <input name="precio" type="number" step="0.01" placeholder="Precio" required><br>
    <input name="peso" placeholder="Peso (ej: 2 kg)" required><br>
    <label>Categoría:</label><br>
    <select name="id_categoria" required>
        <option value="">-- Selecciona una categoría --</option>
        <?php
        $cat_res = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
        while($cat = $cat_res->fetch_assoc()) {
            echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['nombre']) . "</option>";
        }
        $cat_res->free();
        ?>
    </select><br>
    <textarea name="descripcion" placeholder="Descripción" rows="4"></textarea><br>
    <button type="submit">Guardar</button>
    <a class="link" href="productos.php">Cancelar</a>
</form>
</div>
</body>
</html>
<?php
$conexion->close();
?>
