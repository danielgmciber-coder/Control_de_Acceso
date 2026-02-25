<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);

session_start();

header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

// Si ya está logueado, redirigir a principal
if (isset($_SESSION['usuario'])) {
    header('Location: principal.php');
    exit;
}

require 'db.php';


$mensaje = '';
$tipo    = 'error';

function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']    ?? '');
    $password = $_POST['password']       ?? '';
    $confirma = $_POST['confirma']       ?? '';

    // --- Validaciones ---
    if (empty($nombre) || empty($password) || empty($confirma)) {
        $mensaje = 'Por favor, complete todos los campos.';

    } elseif (strlen($nombre) < 3 || strlen($nombre) > 30) {
        $mensaje = 'El nombre de usuario debe tener entre 3 y 30 caracteres.';

    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre)) {
        $mensaje = 'El nombre de usuario solo puede contener letras, números y guiones bajos.';

    } elseif (strlen($password) < 8) {
        $mensaje = 'La contraseña debe tener al menos 8 caracteres.';

    } elseif ($password !== $confirma) {
        $mensaje = 'Las contraseñas no coinciden.';

    } else {
        // Comprobar si el usuario ya existe
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE nombre = :nombre LIMIT 1');
        $stmt->execute(['nombre' => $nombre]);

        if ($stmt->fetch()) {
            $mensaje = 'Ese nombre de usuario ya está en uso. Elige otro.';
        } else {
            // Registrar usuario con hash bcrypt
            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

            $insert = $pdo->prepare(
                'INSERT INTO usuarios (nombre, password, rol) VALUES (:nombre, :password, :rol)'
            );
            $insert->execute([
                'nombre'   => $nombre,
                'password' => $hash,
                'rol'      => 'ROLE_USER',
            ]);

            $tipo    = 'exito';
            $mensaje = '✅ Cuenta creada correctamente. Ya puedes <a href="login.php">iniciar sesión</a>.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; }
        .container {
            width: 320px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; font-size: 0.9em; }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 4px 0 12px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover { background: #1e7e34; }
        .aviso {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        .aviso.error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .aviso.exito {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
        .login-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 0.85em;
            color: #555;
        }
        .login-link a { color: #007bff; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear cuenta</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="aviso <?php echo e($tipo); ?>">
                <?php echo $tipo === 'exito' ? $mensaje : e($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if ($tipo !== 'exito'): ?>
        <form method="POST" action="" autocomplete="off">
            <label for="nombre">Usuario:</label>
            <input type="text" id="nombre" name="nombre" maxlength="30"
                   value="<?php echo isset($_POST['nombre']) ? e($_POST['nombre']) : ''; ?>" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirma">Confirmar contraseña:</label>
            <input type="password" id="confirma" name="confirma" required>

            <button type="submit">Registrarse</button>
        </form>
        <?php endif; ?>

        <span class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></span>
    </div>
</body>
</html>
