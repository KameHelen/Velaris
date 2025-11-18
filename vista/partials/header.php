<?php
// Modo oscuro/claro con cookie
$theme = $_COOKIE['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Un rincón en Velaris</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body class="<?= htmlspecialchars($theme) ?>">
<header>
    <h1>Un rincón en Velaris</h1>
    <nav class="nav-main">
        <a href="<?= BASE_URL ?>/index.php">Inicio</a>

        <!-- Dropdown de géneros -->
        <div class="nav-dropdown">
            <button type="button" class="nav-dropbtn">Géneros ▾</button>
            <div class="nav-dropdown-content">
                <a href="<?= BASE_URL ?>/genero.php?genre=fantasia">Fantasía</a>
                <a href="<?= BASE_URL ?>/genero.php?genre=ciencia-ficcion">Ciencia ficción</a>
                <a href="<?= BASE_URL ?>/genero.php?genre=misterio">Misterio</a>
                <a href="<?= BASE_URL ?>/genero.php?genre=terror">Terror</a>
                <a href="<?= BASE_URL ?>/genero.php?genre=romance">Romance</a>
                <a href="<?= BASE_URL ?>/genero.php?genre=ensayo">Ensayo</a>
            </div>
        </div>

        <?php if (!empty($_SESSION['username'])): ?>
    <?php
   $avatarHeader = $_SESSION['role'] === 'admin'
    ? 'img/admin_avatar.png'
    : (!empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'img/default_avatar.png');

    ?>
    <a href="<?= BASE_URL ?>/perfil.php" class="nav-profile">
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($avatarHeader) ?>"
             alt="Avatar"
             class="nav-avatar">
        <span>Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
    </a>

    <a href="<?= BASE_URL ?>/mis_resenas.php">Mis reseñas</a>
    <a href="<?= BASE_URL ?>/nuevo_libro.php">Nueva reseña</a>

    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="<?= BASE_URL ?>/admin_resenas.php">Gestionar reseñas</a>
        <a href="<?= BASE_URL ?>/admin_usuarios.php">Gestionar usuarios</a>
    <?php endif; ?>
    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="<?= BASE_URL ?>/admin_resenas.php">Gestionar reseñas</a>
    <a href="<?= BASE_URL ?>/admin_usuarios.php">Gestionar usuarios</a>
    <a href="<?= BASE_URL ?>/admin_pendientes.php">Reseñas pendientes</a>   <!-- 🔹 AQUÍ -->
<?php endif; ?>


    <a href="<?= BASE_URL ?>/logout.php">Salir</a>
<?php else: ?>
    <a href="<?= BASE_URL ?>/login.php">Entrar</a>
    <a href="<?= BASE_URL ?>/registro.php">Registrarse</a>
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
