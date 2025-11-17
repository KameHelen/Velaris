<?php include __DIR__ . '/partials/header.php'; ?>

<article>
    <h2><?= htmlspecialchars($post->getTitle()) ?></h2>
    <p><strong>Autor:</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>
    <p><strong>Género:</strong> <?= htmlspecialchars($post->getGenre()) ?></p>

    <?php if ($post->getCoverImage()): ?>
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($post->getCoverImage()) ?>" alt="Portada del libro" style="max-width:300px;">
    <?php endif; ?>

    <div>
        <?= nl2br(htmlspecialchars($post->getContent())) ?>
    </div>
</article>

<?php include __DIR__ . '/partials/footer.php'; ?>
