<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Reseñas pendientes de revisión</h2>

        <?php if (empty($posts)): ?>
            <p>No hay reseñas pendientes.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="card-pendiente">
                    <div class="card-pendiente-header">
                        <div>
                            <h3><?= htmlspecialchars($post->getTitle()) ?></h3>
                            <p class="meta-line">
                                <span><strong>Libro:</strong> <?= htmlspecialchars($post->getAuthor()) ?></span>
                                <span><strong>Género:</strong> <?= htmlspecialchars($post->getGenre()) ?></span>
                            </p>
                            <p class="meta-line">
                                <span><strong>Reseñado por:</strong> <?= htmlspecialchars($post->getUserName()) ?></span>
                                <span class="badge-palabras">
                                    <?= str_word_count(strip_tags($post->getContent())) ?> palabras
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="card-pendiente-body">
                        <h4>Contenido de la reseña</h4>
                        <div class="review-content">
                            <?= nl2br(htmlspecialchars($post->getContent())) ?>
                        </div>
                    </div>

                    <div class="card-pendiente-actions">
                        <a href="admin_pendientes.php?aprobar=<?= $post->getId() ?>"
                           class="btn-aprobar"
                           onclick="return confirm('¿Seguro que quieres aprobar esta reseña y hacerla visible en la web?');">
                            ✓ Aprobar
                        </a>
                        <a href="admin_pendientes.php?rechazar=<?= $post->getId() ?>"
                           class="btn-rechazar"
                           onclick="return confirm('¿Seguro que quieres rechazar (borrar) esta reseña?');">
                            ✗ Rechazar
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
