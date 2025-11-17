<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Mis reseñas</h2>

        <?php if (empty($posts)): ?>
            <p>Aún no has creado ninguna reseña.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Autor del libro</th>
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
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
