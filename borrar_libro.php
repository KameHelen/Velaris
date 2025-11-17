<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controlador = new PostController();
$controlador->borrar($id);
