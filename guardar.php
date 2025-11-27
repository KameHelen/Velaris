<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$controlador = new PostController();
$controlador->toggleGuardar($_GET['id'] ?? 0);
