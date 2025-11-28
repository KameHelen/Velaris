<?php
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

setcookie("pendiente_aprobacion", "", time() - 3600, "/");

header("Location: admin_resenas.php");
exit;
