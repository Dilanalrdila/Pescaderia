<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include "conexion.php";

// Agregar categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_categoria'])) {
    $nombre = trim($_POST['nueva_categoria']);
    if ($nombre !== "") {
        $stmt = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: categorias.php");
    exit;
}

// Eliminar categoría
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = $conexion->prepare("DELETE FROM categorias WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: categorias.php");
    exit;
}

// Consultar categorías
$res = $conexion->query("SELECT * FROM categorias ORDER BY id DESC");
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Categorías</title>
<link rel="stylesheet" href="css/estilo.css">
</head>
<body class="body-bg">
<div class="container">
    <h1>🗂️ Gestión de Categorías</h1>

    <form method="post" action="">
        <input name="nueva_categoria" placeholder="Nombre de categoría" required>
        <button type="submit">Agregar</button>
    </form>

    <br>
    <table class="table-main">
        <tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
        <?php while ($row = $res->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td>
                <a href="editar_categoria.php?id=<?= $row['id'] ?>">✏️ Editar</a> |
                <a href="categorias.php?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar categoría?')">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a class="btn" href="dashboard.php">⬅ Volver al Dashboard</a>
</div>
</body>
</html>

<?php
$conexion->close();
?>
