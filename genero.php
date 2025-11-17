<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$genre = $_GET['genre'] ?? '';
$controlador = new PostController();
$controlador->porGenero($genre);
