<?php
session_start();
require_once __DIR__ . '/controlador/PerfilController.php';

$perfil = new PerfilController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $perfil->actualizarPerfil();
} else {
    $perfil->mostrarPerfil();
}
