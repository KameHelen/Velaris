<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$controlador = new PostController();
$controlador->toggleHeart($_GET['id'] ?? 0);
