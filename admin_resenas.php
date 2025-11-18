<?php if (!empty($_COOKIE['pendiente_aprobacion'])): ?>
    <div class="alerta">
        Hay reseñas largas pendientes de aprobación.
        <a href="aprobar_resenas.php" class="btn">Revisar ahora</a>
    </div>
<?php endif; ?>

<?php
session_start();
require_once __DIR__ . '/controlador/PostController.php';

$controlador = new PostController();
$controlador->adminResenas();
