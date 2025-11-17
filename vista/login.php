<?php include __DIR__ . '/partials/header.php'; ?>

<h2>Acceso a administración</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="" method="post">
    <label>Usuario:
        <input type="text" name="username">
    </label><br>
    <label>Contraseña:
        <input type="password" name="password">
    </label><br>
    <button type="submit">Entrar</button>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
