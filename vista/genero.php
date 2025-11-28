<?php include __DIR__ . '/partials/header.php'; ?>
<main>
    <div class="panel">

<h2>Género: <?= htmlspecialchars($genreSlug ?? '') ?></h2>

<?php if (empty($posts)): ?>
    <p>No hay libros en este género todavía.</p>
<?php else: ?>
<?php foreach ($posts as $post): ?>
    <article>
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

        <p><?= htmlspecialchars(substr($post->getContent(), 0, 150)) ?>...</p>

        <?php
            $likes = Post::contarReacciones($post->getId(), 'like');
            $hearts = Post::contarReacciones($post->getId(), 'heart');
            $userId = $_SESSION['user_id'] ?? null;

            $likedByMe = $userId ? Post::usuarioReacciono($post->getId(), $userId, 'like') : false;
            $heartedByMe = $userId ? Post::usuarioReacciono($post->getId(), $userId, 'heart') : false;
            $savedByMe = $userId ? Post::usuarioGuardo($post->getId(), $userId) : false;
        ?>

        <div class="post-actions-social">
            <a class="social-btn <?= $heartedByMe ? 'active-heart' : '' ?>"
            href="<?= BASE_URL ?>/heart.php?id=<?= $post->getId() ?>">
                ❤️ <span><?= $hearts ?></span>
            </a>

            <a class="social-btn <?= $likedByMe ? 'active-like' : '' ?>"
            href="<?= BASE_URL ?>/like.php?id=<?= $post->getId() ?>">
                👍 <span><?= $likes ?></span>
            </a>

            <a class="social-btn <?= $savedByMe ? 'active-save' : '' ?>"
            href="<?= BASE_URL ?>/guardar.php?id=<?= $post->getId() ?>">
                📌 Guardar
            </a>
        </div>

    </article>

    <div class="star-divider">✧ ✧ ✧</div>

<?php endforeach; ?>

<?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
