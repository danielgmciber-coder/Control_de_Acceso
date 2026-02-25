<?php
require 'auth.php';

if ($_SESSION['rol'] !== 'ROLE_ADMIN') {
    header('Location: no-autorizado.php');
    exit;
}
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; }
        .topbar {
            background: #343a40;
            color: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar span { font-size: 0.95em; }
        .topbar a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.15);
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            margin-left: 8px;
        }
        .topbar a:hover { background: rgba(255,255,255,0.28); }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 { margin-top: 0; color: #343a40; }
        .badge-admin {
            display: inline-block;
            background: #cce5ff;
            color: #004085;
            font-size: 0.75em;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: bold;
            margin-left: 8px;
            vertical-align: middle;
        }
        .info-box {
            background: #e9f7ef;
            border-left: 4px solid #28a745;
            padding: 12px 16px;
            border-radius: 4px;
            margin-top: 16px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <span>⚙️ Panel de Administración &mdash; <strong><?php echo $usuario; ?></strong> <span class="badge-admin">ADMIN</span></span>
        <div>
            <a href="principal.php">← Principal</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
    <div class="container">
        <h1>Panel de Administración</h1>
        <p>Hola, <strong><?php echo $usuario; ?></strong>. Tienes acceso total al sistema.</p>
        <div class="info-box">
            ✅ Área restringida. Solo los usuarios con rol <strong>ROLE_ADMIN</strong> pueden acceder aquí.
        </div>
        <p style="margin-top: 20px; color: #555;">Aquí se gestionan cosas importantes...</p>
    </div>
</body>
</html>