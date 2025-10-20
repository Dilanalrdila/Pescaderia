<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include "conexion.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: productos.php");
    exit;
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $precio = $_POST['precio'] ?? '0';
    $peso = $_POST['peso'] ?? '';
    $id_categoria = $_POST['id_categoria'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';

    $stmt = $conexion->prepare("UPDATE productos SET nombre=?, precio=?, peso=?, id_categoria=?, descripcion=? WHERE id=?");
    $stmt->bind_param("sdsssi", $nombre, $precio, $peso, $id_categoria, $descripcion, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: productos.php");
    exit;
}

// Consultar producto actual
$stmt = $conexion->prepare("
    SELECT p.nombre, p.precio, p.peso, p.id_categoria, p.descripcion, c.nombre AS categoria_nombre
    FROM productos p
    LEFT JOIN categorias c ON p.id_categoria = c.id
    WHERE p.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    $stmt->close();
    $conexion->close();
    header("Location: productos.php");
    exit;
}
$row = $res->fetch_assoc();
$stmt->close();

$nombre = htmlspecialchars($row['nombre']);
$precio = number_format((float)$row['precio'], 2, '.', '');
$peso = htmlspecialchars($row['peso']);
$id_categoria_actual = $row['id_categoria'];
$descripcion = htmlspecialchars($row['descripcion']);
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Editar producto</title>
<link rel="stylesheet" href="css/estilo.css">
</head>
<body class="body-bg">
<div class="box">
<h2>Editar producto #<?= $id ?></h2>
<form method="post" action="">
    <label>Nombre:</label><br>
    <input name="nombre" value="<?= $nombre ?>" required><br>

    <label>Precio:</label><br>
    <input name="precio" type="number" step="0.01" value="<?= $precio ?>" required><br>

    <label>Peso:</label><br>
    <input name="peso" value="<?= $peso ?>" required><br>

    <label>Categoría:</label><br>
    <select name="id_categoria" required>
        <option value="">-- Selecciona una categoría --</option>
        <?php
        $cat_res = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
        while ($cat = $cat_res->fetch_assoc()) {
            $selected = ($cat['id'] == $id_categoria_actual) ? "selected" : "";
            echo "<option value='{$cat['id']}' $selected>" . htmlspecialchars($cat['nombre']) . "</option>";
        }
        $cat_res->free();
        ?>
    </select><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" rows="4"><?= $descripcion ?></textarea><br>

    <button type="submit">Actualizar</button>
    <a class="link" href="productos.php">Cancelar</a>
</form>
</div>
</body>
</html>

<?php
$conexion->close();
?>
