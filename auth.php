<?php

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php?error=auth');
    exit;
}

?>