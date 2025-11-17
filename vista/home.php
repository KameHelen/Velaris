<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">

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
            <p><?= htmlspecialchars(substr($post->getContent(), 0, 150)) ?>...</p>
        </article>
       <div class="star-divider">✧ ✧ ✧</div>
    <?php endforeach; ?>
<?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
