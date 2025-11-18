<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Reseñas pendientes de revisión</h2>

        <?php if (!empty($_COOKIE['resenas_pendientes'])): ?>
            <div class="alerta">
                Hay reseñas largas pendientes de aprobación.
            </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <p>No hay reseñas pendientes.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Autor del libro</th>
                    <th>Género</th>
                    <th>Reseñado por</th>
                    <th>Palabras</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post->getTitle()) ?></td>
                        <td><?= htmlspecialchars($post->getAuthor()) ?></td>
                        <td><?= htmlspecialchars($post->getGenre()) ?></td>
                        <td><?= htmlspecialchars($post->getUserName()) ?></td>
                        <td><?= str_word_count(strip_tags($post->getContent())) ?></td>
                        <td>
                            <a href="admin_pendientes.php?aprobar=<?= $post->getId() ?>">Aprobar</a> |
                            <a href="admin_pendientes.php?rechazar=<?= $post->getId() ?>"
                               onclick="return confirm('¿Seguro que quieres rechazar esta reseña?');">
                                Rechazar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
