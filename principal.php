<?php
require 'auth.php';
$usuario = htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8');
$rol     = htmlspecialchars($_SESSION['rol'],     ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; }
        .topbar {
            background: #007bff;
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
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .topbar a:hover { background: rgba(255,255,255,0.35); }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 { margin-top: 0; color: #333; }
        .badge {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            font-size: 0.8em;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: bold;
            margin-left: 8px;
            vertical-align: middle;
        }
        .badge.admin { background: #cce5ff; color: #004085; }
        ul { padding-left: 20px; line-height: 2; }
        ul a { color: #007bff; text-decoration: none; }
        ul a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="topbar">
        <span>👤 Conectado como <strong><?php echo $usuario; ?></strong></span>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="container">
        <h1>Bienvenido, <?php echo $usuario; ?> 
            <span class="badge <?php echo $rol === 'ROLE_ADMIN' ? 'admin' : ''; ?>">
                <?php echo $rol; ?>
            </span>
        </h1>
        <p>Estás en la página principal. Acceso disponible para todos los usuarios registrados.</p>
        <ul>
            <?php if ($rol === 'ROLE_ADMIN'): ?>
            <li><a href="admin.php">⚙️ Ir al Panel de Administración</a></li>
            <?php endif; ?>
            <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>
</body>
</html>