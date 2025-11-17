<?php include __DIR__ . '/partials/header.php'; ?>

<main>
    <div class="panel">
        <h2>Gestionar usuarios</h2>

        <table>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Fecha creación</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars($u['created_at']) ?></td>
                    <td>
                        <?php if ($u['username'] === 'admin'): ?>
                            <em>Admin principal</em>
                        <?php else: ?>
                            <?php if ($u['role'] === 'banned'): ?>
                                <a href="admin_usuarios.php?accion=unban&id=<?= (int)$u['id'] ?>">
                                    Desbanear
                                </a>
                            <?php else: ?>
                                <a href="admin_usuarios.php?accion=ban&id=<?= (int)$u['id'] ?>"
                                   onclick="return confirm('¿Seguro que quieres bloquear este usuario?');">
                                    Banear
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
