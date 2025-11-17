<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Gestionar reseñas</h2>

        <?php
        $genres = [
            'todos'          => 'Todos los géneros',
            'fantasia'        => 'Fantasía',
            'ciencia-ficcion' => 'Ciencia ficción',
            'misterio'        => 'Misterio',
            'terror'          => 'Terror',
            'romance'         => 'Romance',
            'ensayo'          => 'Ensayo'
        ];
        ?>

        <form method="get" action="admin_resenas.php" style="margin-bottom:20px;">
            <label>Filtrar por género:
                <select name="genre">
                    <?php foreach ($genres as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($selectedGenre === $value) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Filtrar</button>
        </form>

        <table>
            <tr>
                <th>Título</th>
                <th>Autor del libro</th>
                <th>Género</th>
                <th>Reseñado por</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post->getTitle()) ?></td>
                    <td><?= htmlspecialchars($post->getAuthor()) ?></td>
                    <td><?= htmlspecialchars($post->getGenre()) ?></td>
                    <td><?= htmlspecialchars($post->getUserName()) ?></td>
                    <td>
                        <?php
                        $esDueno = isset($_SESSION['user_id']) && $post->getUserId() === $_SESSION['user_id'];
                        $esAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
                        ?>

                        <?php if ($esDueno): ?>
                            <a href="editar_libro.php?id=<?= $post->getId() ?>">Editar</a> |
                        <?php endif; ?>

                        <?php if ($esDueno || $esAdmin): ?>
                            <a href="borrar_libro.php?id=<?= $post->getId() ?>"
                               onclick="return confirm('¿Seguro que quieres borrar esta reseña?');">
                                Borrar
                            </a>
                        <?php else: ?>
                            <em>Sin permisos</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
