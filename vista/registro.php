<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
    <div class="card">
        <h2>Crear cuenta</h2>

        <?php if (!empty($error)): ?>
            <p style="color:#c0392b;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label>Usuario:
                <input type="text" name="username"
                       value="<?= isset($old['username']) ? htmlspecialchars($old['username']) : '' ?>">
            </label>

            <label>Contraseña:
                <input type="password" name="password">
            </label>

            <label>Repite la contraseña:
                <input type="password" name="password2">
            </label>

            <button type="submit">Registrarme</button>
        </form>

        <p style="margin-top:15px; text-align:center;">
            ¿Ya tienes cuenta?
            <a href="login.php">Inicia sesión</a>
        </p>
    </div>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
