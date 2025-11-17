<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$slug = $_GET['slug'] ?? '';
$controlador = new PostController();
$controlador->mostrar($slug);
