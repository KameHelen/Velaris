<?php
session_start();
require_once __DIR__ . '/controlador/AuthController.php';

$auth = new AuthController();
$auth->logout();
