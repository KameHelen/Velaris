<?php
// controlador/AuthController.php
require_once __DIR__ . '/../modelo/User.php';

class AuthController {

    public function mostrarLogin() {
        $error = null;
        include __DIR__ . '/../vista/login.php';
    }

  public function login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = User::findByUsername($username);

    if ($user && $user->verifyPassword($password)) {

        // Si está baneado -> no le dejamos entrar
        if ($user->getRole() === 'banned') {
            $error = "Tu cuenta ha sido bloqueada por un administrador.";
            include __DIR__ . '/../vista/login.php';
            return;
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['role'] = $user->getRole();

        // Redirección según rol
        if ($user->getRole() === 'admin') {
            header("Location: admin_resenas.php");
        } else {
            header("Location: mis_resenas.php");
        }
        exit;

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

public function mostrarRegistro() {
    $error = null;
    $old = [];
    include __DIR__ . '/../vista/registro.php';
}

public function registrar() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    $error = null;
    $old = ['username' => $username];

    // Validaciones básicas
    if ($username === '' || strlen($username) < 3) {
        $error = "El nombre de usuario debe tener al menos 3 caracteres.";
    } elseif ($password === '' || strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($password !== $password2) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // ¿ya existe?
        $existing = User::findByUsername($username);
        if ($existing) {
            $error = "Ese nombre de usuario ya está en uso.";
        }
    }

    if ($error !== null) {
        include __DIR__ . '/../vista/registro.php';
        return;
    }

    // Crear usuario
    $creado = User::create($username, $password);

    if ($creado) {
        // opcional: iniciar sesión directamente
        // o redirigir a login
        header("Location: login.php");
        exit;
    } else {
        $error = "Ha habido un error al crear el usuario.";
        include __DIR__ . '/../vista/registro.php';
    }
}


}
