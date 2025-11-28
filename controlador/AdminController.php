<?php
// controlador/AdminController.php
require_once __DIR__ . '/../modelo/Database.php';
require_once __DIR__ . '/../modelo/User.php';

class AdminController {

    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
    }

    public function gestionarUsuarios() {
        $this->requireAdmin();

        $pdo = Database::getConexion();
        $stmt = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
        $usuarios = $stmt->fetchAll();

        include __DIR__ . '/../vista/admin_usuarios.php';
    }

    public function actualizarEstadoUsuario(int $id, string $accion) {
        $this->requireAdmin();

        if ($id <= 0) {
            header("Location: admin_usuarios.php");
            return;
        }

        if ($accion === 'ban') {
            $nuevoRol = 'banned';
        } elseif ($accion === 'unban') {
            $nuevoRol = 'user';
        } else {
            header("Location: admin_usuarios.php");
            return;
        }

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id AND username <> 'admin'");
        $stmt->execute([
            ':role' => $nuevoRol,
            ':id'   => $id
        ]);

        header("Location: admin_usuarios.php");
    }
}
