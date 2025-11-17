<?php include __DIR__ . '/partials/header.php'; ?>

<h2>Administrar reseñas</h2>

<p><a href="nuevo_libro.php">Nueva reseña</a></p>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Género</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= htmlspecialchars($post->getTitle()) ?></td>
            <td><?= htmlspecialchars($post->getAuthor()) ?></td>
            <td><?= htmlspecialchars($post->getGenre()) ?></td>
            <td>
                <a href="editar_libro.php?id=<?= $post->getId() ?>">Editar</a>
                |
                <a href="borrar_libro.php?id=<?= $post->getId() ?>"
                   onclick="return confirm('¿Seguro que quieres borrar esta reseña?');">
                   Borrar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/partials/footer.php'; ?>
