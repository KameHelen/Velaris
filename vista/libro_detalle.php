<?php include __DIR__ . '/partials/header.php'; ?>
<main>
<div class="panel">
<article>
    <h2><?= htmlspecialchars($post->getTitle()) ?></h2>
    <p><strong>Autor:</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>
    <?php
$avatarResena = $post->getUserAvatar();

?>
<p class="reseñado-por">
    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($avatarResena) ?>"
         alt="Avatar reseñador"
         class="post-avatar">
    <span><strong>Reseñado por:</strong> <?= htmlspecialchars($post->getUserName()) ?></span>
</p>

    <p><strong>Género:</strong> <?= htmlspecialchars($post->getGenre()) ?></p>

    <?php if ($post->getCoverImage()): ?>
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($post->getCoverImage()) ?>" alt="Portada del libro" style="max-width:300px;">
    <?php endif; ?>

    <div>
        <?= nl2br(htmlspecialchars($post->getContent())) ?>
    </div>
</article>
</div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
