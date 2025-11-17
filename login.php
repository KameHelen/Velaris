<?php
session_start();
require_once __DIR__ . '/controlador/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->login();
} else {
    $auth->mostrarLogin();
}
