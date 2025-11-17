<?php
// controlador/AuthController.php
require_once __DIR__ . '/../modelo/User.php';

class AuthController {

    public function mostrarLogin() {
        $error = null;
        include __DIR__ . '/../vista/login.php';
    }

    public function login() {
    session_start();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = User::findByUsername($username);

    if ($user && $user->verifyPassword($password)) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        header("Location: admin_libros.php");
    } else {
        $error = "Usuario o contraseña incorrectos.";
        include __DIR__ . '/../vista/login.php';
    }
}


    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
}
