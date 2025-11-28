<?php
// controlador/PerfilController.php
require_once __DIR__ . '/../modelo/User.php';

class PerfilController {

    private function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }

    public function mostrarPerfil() {
        $this->requireLogin();

        $user = User::findById($_SESSION['user_id']);
        if (!$user) {
            echo "Usuario no encontrado.";
            return;
        }

        $errores = [];
        $mensaje = null;

        include __DIR__ . '/../vista/perfil.php';
    }

    public function actualizarPerfil() {
        $this->requireLogin();

        $user = User::findById($_SESSION['user_id']);
        if (!$user) {
            echo "Usuario no encontrado.";
            return;
        }

        $errores = [];
        $mensaje = null;

        $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];
        $favoritos = $_POST['favorite_genres'] ?? [];
        if (!is_array($favoritos)) {
            $favoritos = [];
        }
        $favoritos = array_values(array_intersect($favoritos, $allowedGenres));
        $favoriteString = !empty($favoritos) ? implode(',', $favoritos) : null;

        // ===== Avatar =====
     
$avatarPath = $user->getProfileImage();

if ($user->getRole() === 'admin') {
    // admin imagen fija
    $avatarPath = 'img/admin_avatar.png';
} else {
    // Solo usuarios normales pueden subir avatar
    if (!empty($_FILES['avatar']['name'])) {
        if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $mime = mime_content_type($_FILES['avatar']['tmp_name']);
            $allowedMime = ['image/jpeg','image/png'];

            if (!in_array($mime, $allowedMime)) {
                $errores[] = "La imagen de perfil debe ser JPG o PNG.";
            } elseif ($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
                $errores[] = "La imagen de perfil no puede superar los 5MB.";
            } else {
                if ($avatarPath && strpos($avatarPath, 'uploads/avatars/') === 0) {
                    $oldPath = __DIR__ . '/../' . $avatarPath;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $ext = $mime === 'image/jpeg' ? '.jpg' : '.png';
                $nuevoNombre = uniqid('avatar_', true) . $ext;
                $destino = __DIR__ . '/../uploads/avatars/' . $nuevoNombre;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destino)) {
                    $avatarPath = 'uploads/avatars/' . $nuevoNombre;
                } else {
                    $errores[] = "Error al guardar la imagen de perfil.";
                }
            }
        } else {
            $errores[] = "Error al subir la imagen de perfil.";
        }
    }
}
if (!empty($errores)) {
    $mensaje = null;
    $user->setProfileImage($avatarPath);
    $user->setFavoriteGenres($favoriteString);
    include __DIR__ . '/../vista/perfil.php';
    return;
}

$user->setProfileImage($avatarPath);
$user->setFavoriteGenres($favoriteString);
$user->updateProfile();


        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $newPassword2    = $_POST['new_password2'] ?? '';

        $quiereCambiarPass = ($currentPassword !== '' || $newPassword !== '' || $newPassword2 !== '');

        if ($quiereCambiarPass) {
            if ($currentPassword === '' || $newPassword === '' || $newPassword2 === '') {
                $errores[] = "Para cambiar la contraseña debes rellenar todos los campos de contraseña.";
            } elseif (!$user->verifyPassword($currentPassword)) {
                $errores[] = "La contraseña actual no es correcta.";
            } elseif (strlen($newPassword) < 6) {
                $errores[] = "La nueva contraseña debe tener al menos 6 caracteres.";
            } elseif ($newPassword !== $newPassword2) {
                $errores[] = "Las nuevas contraseñas no coinciden.";
            }
        }

        if (!empty($errores)) {
            $mensaje = null;
            $user->setProfileImage($avatarPath);
            $user->setFavoriteGenres($favoriteString);
            include __DIR__ . '/../vista/perfil.php';
            return;
        }

        $user->setProfileImage($avatarPath);
        $user->setFavoriteGenres($favoriteString);
        $user->updateProfile();

        if ($quiereCambiarPass) {
            $user->updatePassword($newPassword);
        }

        $_SESSION['profile_image'] = $avatarPath;

        $mensaje = "Perfil actualizado correctamente.";
        $errores = [];
        include __DIR__ . '/../vista/perfil.php';
    }
}
