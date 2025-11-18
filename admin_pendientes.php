<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$controlador = new PostController();

if (isset($_GET['aprobar'])) {
    $controlador->aprobarPendiente((int)$_GET['aprobar']);
} elseif (isset($_GET['rechazar'])) {
    $controlador->rechazarPendiente((int)$_GET['rechazar']);
} else {
    $controlador->listarPendientes();
}
