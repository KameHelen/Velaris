<?php
// Modo oscuro/claro con cookie
$theme = $_COOKIE['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Un rincón en Velaris</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body class="<?= htmlspecialchars($theme) ?>">
<header>
    <h1>Un rincón en Velaris</h1>
    <nav>
        <a href="<?= BASE_URL ?>/index.php">Inicio</a>
        <a href="<?= BASE_URL ?>/genero.php?genre=fantasia">Fantasía</a>
        <a href="<?= BASE_URL ?>/genero.php?genre=ciencia-ficcion">Ciencia ficción</a>
        <a href="<?= BASE_URL ?>/genero.php?genre=misterio">Misterio</a>
        <a href="<?= BASE_URL ?>/genero.php?genre=terror">Terror</a>
        <a href="<?= BASE_URL ?>/genero.php?genre=romance">Romance</a>
        <a href="<?= BASE_URL ?>/genero.php?genre=ensayo">Ensayo</a>
        <a href="<?= BASE_URL ?>/admin_libros.php">Admin</a>
        <?php if (!empty($_SESSION['username'])): ?>
            <span>Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="<?= BASE_URL ?>/logout.php">Salir</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/login.php">Entrar</a>
        <?php endif; ?>
    </nav>
    <button id="toggle-theme">Modo oscuro / claro</button>
</header>

<script>
document.getElementById('toggle-theme').addEventListener('click', function () {
    var isDark = document.body.classList.contains('dark');
    var newTheme = isDark ? 'light' : 'dark';
    document.cookie = "theme=" + newTheme + "; path=/; max-age=" + (60*60*24*30);
    location.reload();
});
</script>
