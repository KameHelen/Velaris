<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Reseñas guardadas</h2>

        <?php if (empty($posts)): ?>
            <p>No has guardado ninguna reseña todavía.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <h3>
                        <a href="libro.php?slug=<?= htmlspecialchars($post->getSlug()) ?>">
                            <?= htmlspecialchars($post->getTitle()) ?>
                        </a>
                    </h3>
                    <p><strong>Autor:</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>
                    <p><strong>Reseñado por:</strong> <?= htmlspecialchars($post->getUserName()) ?></p>
                </article>

                <div class="star-divider">✧ ✧ ✧</div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
