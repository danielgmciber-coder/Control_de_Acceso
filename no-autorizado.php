<?php
require 'auth.php';
$usuario = htmlspecialchars($_SESSION['usuario'] ?? 'Visitante', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; }
        .container {
            max-width: 480px;
            margin: 80px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }
        .icon { font-size: 3em; margin-bottom: 10px; }
        h1 { color: #dc3545; margin-top: 0; }
        p { color: #555; line-height: 1.6; }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 24px;
            background: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.95em;
        }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🚫</div>
        <h1>Acceso Denegado</h1>
        <p>Lo sentimos, <strong><?php echo $usuario; ?></strong>.<br>
        No tienes permisos suficientes para ver esa página.<br>
        <small style="color:#888;">(Rol requerido: ROLE_ADMIN)</small></p>
        <a href="principal.php" class="btn">← Volver a un lugar seguro</a>
    </div>
</body>
</html>