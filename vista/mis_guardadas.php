<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">

        <h2>Reseñas guardadas</h2>

        <?php if (empty($posts)): ?>
            <p>No has guardado ninguna reseña todavía.</p>
        <?php else: ?>
       <?php foreach ($posts as $post): ?>
<article class="post-card">

    <?php if ($post->getCoverImage()): ?>
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($post->getCoverImage()) ?>"
             alt="Portada"
             class="post-cover-resena">
    <?php endif; ?>

    <div class="post-info">
        <h3>
            <a href="libro.php?slug=<?= htmlspecialchars($post->getSlug()) ?>">
                <?= htmlspecialchars($post->getTitle()) ?>
            </a>
        </h3>

        <p><strong>Autor:</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>

        <?php $avatarResena = $post->getUserAvatar(); ?>
        <p class="reseñado-por">
            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($avatarResena) ?>"
                 alt="Avatar reseñador"
                 class="post-avatar">
            <span><strong>Reseñado por:</strong> <?= htmlspecialchars($post->getUserName()) ?></span>
        </p>
    </div>

</article>

<div class="star-divider">✧ ✧ ✧</div>
<?php endforeach; ?>

        <?php endif; ?>

    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
