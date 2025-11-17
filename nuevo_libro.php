<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$controlador = new PostController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador->guardarNuevo();
} else {
    $controlador->crearForm();
}
