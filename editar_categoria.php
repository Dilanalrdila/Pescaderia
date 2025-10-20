<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include "conexion.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: categorias.php");
    exit;
}

// Guardar cambios si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre !== '') {
        $stmt = $conexion->prepare("UPDATE categorias SET nombre=? WHERE id=?");
        $stmt->bind_param("si", $nombre, $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categorias.php");
    exit;
}

// Obtener categoría actual
$stmt = $conexion->prepare("SELECT nombre FROM categorias WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conexion->close();
    header("Location: categorias.php");
    exit;
}

$row = $result->fetch_assoc();
$nombre_actual = htmlspecialchars($row['nombre']);
$stmt->close();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar Categoría</title>
<link rel="stylesheet" href="css/estilo.css">
</head>
<body class="body-bg">
<div class="box">
<h2>Editar Categoría</h2>
<form method="post">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?= $nombre_actual ?>" required><br><br>
    <button type="submit">Guardar cambios</button>
    <a class="link" href="categorias.php">Cancelar</a>
</form>
</div>
</body>
</html>

<?php
$conexion->close();
?>
