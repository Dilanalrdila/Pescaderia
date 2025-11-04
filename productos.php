<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include "conexion.php";

// Consultar productos con la categoría (JOIN)
$stmt = $conexion->prepare("
SELECT p.id, p.nombre, p.precio, p.peso, c.nombre AS categoria, p.descripcion
FROM productos p
LEFT JOIN categorias c ON p.id_categoria = c.id
ORDER BY p.id DESC
");
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Productos</title>
<link rel="stylesheet" href="css/estilo.css">
<style>
    table {
        border-collapse: collapse; /* reemplaza cellspacing */
        width: 100%;
        margin-top: 15px;
    }
    table th, table td {
        border: 1px solid #ccc;
        padding: 8px; /* reemplaza cellpadding */
    }
    table th {
        background: #eee;
    }
</style>
</head>
<body class="body-bg">
<div class="box">
    <h2>Gestión de Productos</h2>
    <a class="btn" href="agregar.php">➕ Agregar producto</a>
    <a class="btn" href="categorias.php">📂 Categorías</a>
    <a class="btn" href="dashboard.php">📊 Dashboard</a>
    <a class="btn" href="logout.php">🔒 Cerrar sesión</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Peso</th>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td>$<?= number_format($row['precio'], 2) ?></td>
            <td><?= htmlspecialchars($row['peso']) ?></td>
            <td><?= htmlspecialchars($row['categoria'] ?? 'Sin categoría') ?></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td>
                <a href="editar.php?id=<?= $row['id'] ?>">✏️ Editar</a> |
                <a href="eliminar.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este producto?')">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
<?php
$stmt->close();
$conexion->close();
?>
