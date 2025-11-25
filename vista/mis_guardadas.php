<?php include __DIR__ . '/partials/header.php'; ?>

<main>
  <div class="panel">
    <h2>Mis reseñas guardadas</h2>

    <?php if (empty($posts)): ?>
        <p>No tienes reseñas guardadas aún.</p>
    <?php else: ?>
        <div class="grid-posts">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <?php if ($post->getCoverImage()): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($post->getCoverImage()) ?>" class="post-cover">
                <?php endif; ?>

                <h3>
                    <?= htmlspecialchars($post->getTitle()) ?>
                    <span class="badge-draft">(Guardada)</span>
                </h3>
                <p><strong>Autor:</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>
                <p><strong>Género:</strong> <?= htmlspecialchars($post->getGenre()) ?></p>

                <div class="post-actions">
                    <a class="btn-link" href="<?= BASE_URL ?>/editar_libro.php?id=<?= $post->getId() ?>">
                        Editar
                    </a>
                    <a class="btn-link danger"
                       href="<?= BASE_URL ?>/borrar_libro.php?id=<?= $post->getId() ?>"
                       onclick="return confirm('¿Seguro que quieres borrar este borrador?');">
                        Borrar
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
