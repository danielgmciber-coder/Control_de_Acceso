<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);

session_start();

header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

require 'db.php';

$mensaje = "";

function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($password)) {
        $mensaje = "Por favor, complete todos los campos.";
    } else {
        $stmt = $pdo->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE nombre = :nombre LIMIT 1");
        $stmt->execute(['nombre' => $nombre]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        $loginExitoso = false;
        $actualizarHash = false;

        if ($usuario) {
            if (password_verify($password, $usuario['password'])) {
                $loginExitoso = true;
            } 
            elseif (md5($password) === $usuario['password']) {
                $loginExitoso = true;
                $actualizarHash = true;
            }
        }

        if ($loginExitoso) {
            session_regenerate_id(true);

            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol']     = $usuario['rol'];
            
            if ($actualizarHash) {
                $nuevoHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
                
                $updateStmt = $pdo->prepare("UPDATE usuarios SET password = :pass WHERE id = :id");
                $updateStmt->execute([
                    'pass' => $nuevoHash,
                    'id'   => $usuario['id']
                ]);
            }

            header('Location: principal.php');
            exit;

        } else {
            sleep(1); 
            $mensaje = "Credenciales incorrectas.";
        }
    }
}

if (isset($_GET['error']) && $_GET['error'] === 'auth') {
    $mensaje = "Debe iniciar sesión para continuar.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Seguro</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; }
        .error { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px;
        }
        .container { 
            width: 300px; 
            margin: 50px auto; 
            padding: 20px; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        input { width: 100%; padding: 8px; margin: 5px 0 15px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Iniciar Sesión</h2>
        
        <?php if (!empty($mensaje)): ?>
            <p class="error"><?php echo e($mensaje); ?></p>
        <?php endif; ?>

        <form method="POST" action="" autocomplete="off">
            <label for="nombre">Usuario:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?php echo isset($_POST['nombre']) ? e($_POST['nombre']) : ''; ?>" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Entrar</button>
        </form>
        <p style="text-align: center; margin-top: 14px; font-size: 0.85em; color: #555;">
            ¿No tienes cuenta? <a href="registro.php" style="color: #007bff; text-decoration: none;">Regístrate</a>
        </p>
    </div>
</body>
</html>