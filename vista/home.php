<?php include __DIR__ . '/partials/header.php'; ?>

<h2>Últimas reseñas</h2>

<?php if (empty($posts)): ?>
    <p>Aún no hay reseñas.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <article>
            <h3>
                <a href="libro.php?slug=<?= htmlspecialchars($post->getSlug()) ?>">
                    <?= htmlspecialchars($post->getTitle()) ?>
                </a>
            </h3>
            <p><strong>Autor:</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>
            <p><strong>Género:</strong> <?= htmlspecialchars($post->getGenre()) ?></p>
            <p><?= htmlspecialchars(substr($post->getContent(), 0, 150)) ?>...</p>
        </article>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
