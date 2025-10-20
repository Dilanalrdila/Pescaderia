<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include "conexion.php";

// Consultar estadísticas
$total_productos = 0;
$precio_promedio = 0;
$producto_caro = "";
$producto_barato = "";
$total_categorias = 0;

// Total de productos
$res = $conexion->query("SELECT COUNT(*) AS total FROM productos");
if ($row = $res->fetch_assoc()) {
    $total_productos = $row['total'];
}
$res->free();

// Promedio de precios
$res = $conexion->query("SELECT AVG(precio) AS promedio FROM productos");
if ($row = $res->fetch_assoc()) {
    $precio_promedio = number_format($row['promedio'], 2);
}
$res->free();

// Producto más caro
$res = $conexion->query("SELECT nombre, precio FROM productos ORDER BY precio DESC LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $producto_caro = $row['nombre'] . " ($" . number_format($row['precio'], 2) . ")";
}
$res->free();

// Producto más barato
$res = $conexion->query("SELECT nombre, precio FROM productos ORDER BY precio ASC LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $producto_barato = $row['nombre'] . " ($" . number_format($row['precio'], 2) . ")";
}
$res->free();

// Cantidad de categorías distintas
$res = $conexion->query("SELECT COUNT(DISTINCT categoria) AS total FROM productos");
if ($row = $res->fetch_assoc()) {
    $total_categorias = $row['total'];
}
$res->free();

$conexion->close();
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Dashboard - Tienda PHP</title>
<link rel="stylesheet" href="css/estilo.css">
</head>
<body class="body-bg">
<div class="container">
    <h1>📊 Dashboard de Productos</h1>
    <p>Bienvenido, <b><?= htmlspecialchars($_SESSION['user']) ?></b></p>
    <hr>

    <div class="stats">
        <div class="card">
            <h3>Total de productos</h3>
            <p><?= $total_productos ?></p>
        </div>

        <div class="card">
            <h3>Precio promedio</h3>
            <p>$<?= $precio_promedio ?></p>
        </div>

        <div class="card">
            <h3>Producto más caro</h3>
            <p><?= $producto_caro ?: "N/A" ?></p>
        </div>

        <div class="card">
            <h3>Producto más barato</h3>
            <p><?= $producto_barato ?: "N/A" ?></p>
        </div>

        <div class="card">
            <h3>Categorías distintas</h3>
            <p><?= $total_categorias ?></p>
        </div>
    </div>

    <hr>
    <a class="btn" href="productos.php">📦 Ver productos</a>
    <a class="btn danger" href="logout.php">🚪 Cerrar sesión</a>
</body>
</html>
