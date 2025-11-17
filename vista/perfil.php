<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Mi perfil</h2>

        <?php
        $avatarActual = $user->getProfileImage() ?: 'img/default_avatar.png';
        $favoriteString = $user->getFavoriteGenres();
        $favoriteArray = $favoriteString ? explode(',', $favoriteString) : [];
        $genres = [
            'fantasia'        => 'Fantasía',
            'ciencia-ficcion' => 'Ciencia ficción',
            'misterio'        => 'Misterio',
            'terror'          => 'Terror',
            'romance'         => 'Romance',
            'ensayo'          => 'Ensayo'
        ];
        ?>

        <?php if (!empty($errores)): ?>
            <ul style="color:#c0392b;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (!empty($mensaje)): ?>
            <p style="color:green;"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data" class="card">
            <h3>Información básica</h3>
            <p><strong>Usuario:</strong> <?= htmlspecialchars($user->getUsername()) ?></p>

            <h3>Foto de perfil</h3>
<p>
    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($avatarActual) ?>"
         style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
</p>

<?php if ($user->getRole() !== 'admin'): ?>
    <label>Nueva imagen de perfil:
        <input type="file" name="avatar">
    </label>
<?php else: ?>
    <p><em>El administrador no puede cambiar su imagen de perfil.</em></p>
<?php endif; ?>


            <h3>Géneros favoritos</h3>
            <p>Selecciona tus géneros favoritos:</p>

            <?php foreach ($genres as $value => $label): ?>
                <label style="display:block; text-align:left;">
                    <input type="checkbox" name="favorite_genres[]"
                           value="<?= $value ?>"
                           <?= in_array($value, $favoriteArray) ? 'checked' : '' ?>>
                    <?= $label ?>
                </label>
            <?php endforeach; ?>

            <h3>Cambiar contraseña</h3>
            <p>(Opcional, deja en blanco si no quieres cambiarla)</p>
            <label>Contraseña actual:
                <input type="password" name="current_password">
            </label>
            <label>Nueva contraseña:
                <input type="password" name="new_password">
            </label>
            <label>Repite la nueva contraseña:
                <input type="password" name="new_password2">
            </label>

            <button type="submit">Guardar cambios</button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
