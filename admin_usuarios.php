<?php
session_start();
require_once __DIR__ . '/controlador/AdminController.php';

$admin = new AdminController();

if (isset($_GET['accion'], $_GET['id'])) {
    $admin->actualizarEstadoUsuario((int)$_GET['id'], $_GET['accion']);
} else {
    $admin->gestionarUsuarios();
}
