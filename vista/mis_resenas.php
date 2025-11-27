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
<article class="post-card">

    <!-- 📚 Portada -->
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

        <p><strong>Género:</strong> <?= htmlspecialchars($post->getGenre()) ?></p>
        <p><?= htmlspecialchars(substr($post->getContent(), 0, 150)) ?>...</p>

        <!-- Iconos ❤️ 👍 📌 si quieres -->
        <!-- aquí puedes pegar también tu bloque social -->
    </div>

</article>

<div class="star-divider">✧ ✧ ✧</div>
<?php endforeach; ?>


            </table>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
