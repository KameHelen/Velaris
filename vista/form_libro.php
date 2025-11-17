<?php include __DIR__ . '/partials/header.php'; ?>
<main>
    <div class="panel">
<?php
$isEdit = isset($postData['id']);
?>

<h2><?= $isEdit ? 'Editar reseña' : 'Nueva reseña' ?></h2>

<?php if (!empty($errores)): ?>
    <ul style="color:red;">
        <?php foreach ($errores as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <label>Título:
        <input type="text" name="title"
               value="<?= htmlspecialchars($postData['title'] ?? '') ?>">
    </label><br>

    <label>Autor:
        <input type="text" name="author"
               value="<?= htmlspecialchars($postData['author'] ?? '') ?>">
    </label><br>

    <label>Género:
        <?php
        $selectedGenre = $postData['genre'] ?? 'fantasia';
        $genres = [
            'fantasia'        => 'Fantasía',
            'ciencia-ficcion' => 'Ciencia ficción',
            'misterio'        => 'Misterio',
            'terror'          => 'Terror',
            'romance'         => 'Romance',
            'ensayo'          => 'Ensayo'
        ];
        ?>
        <select name="genre">
            <?php foreach ($genres as $value => $label): ?>
                <option value="<?= $value ?>" <?= $selectedGenre === $value ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>

    <label>Reseña:
        <textarea name="content" rows="8"><?= htmlspecialchars($postData['content'] ?? '') ?></textarea>
    </label><br>

    <?php if (!empty($currentCover)): ?>
        <p>Portada actual:</p>
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($currentCover) ?>" alt="Portada actual" style="max-width:200px;"><br>
        <label>
            <input type="checkbox" name="remove_cover" value="1">
            Quitar portada
        </label><br>
    <?php endif; ?>

    <label>Nueva portada (opcional):
        <input type="file" name="cover">
    </label><br>

    <button type="submit"><?= $isEdit ? 'Actualizar' : 'Guardar' ?></button>
</form>
</div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
