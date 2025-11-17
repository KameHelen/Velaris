<?php
session_start();

// solo admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Borrar cookie
setcookie("pendiente_aprobacion", "", time() - 3600, "/");

// Redirigir de nuevo
header("Location: admin_resenas.php");
exit;
