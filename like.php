<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';
(new PostController())->toggleLike($_GET['id'] ?? 0);
