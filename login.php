<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['usuario'] = $_POST['usuario'] ?? 'invitado';
    header("Location: dashboard.php");
    exit;
}

echo <<<'PHPHTML'
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Iniciar sesión - Tienda PHP</title>
<link rel="stylesheet" href="css/estilo.css">
</head>
<body class="body-bg">
<div class="login-box">
<h2>Iniciar sesión</h2>
<form method="post" action="">
<input name="usuario" placeholder="Usuario" required><br>
<input name="clave" type="password" placeholder="Contraseña" required><br>
<button type="submit">Entrar</button>
</form>
</div>
</body>
</html>
PHPHTML;
