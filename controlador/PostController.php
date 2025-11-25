<?php
// controlador/PostController.php
require_once __DIR__ . '/../modelo/Post.php';

class PostController {

    // ========== PÚBLICO ==========

    // LISTADO PÚBLICO
 public function index() {
    $posts = Post::obtenerPublicados();
    include __DIR__ . '/../vista/home.php';
}


    // DETALLE LIBRO
  public function mostrar($slug) {
    $post = Post::obtenerPublicadoPorSlug($slug);

    if (!$post) {
        http_response_code(404);
        echo "Libro no encontrado";
        return;
    }
    include __DIR__ . '/../vista/libro_detalle.php';
}


    // FILTRO POR GÉNERO
  public function porGenero($genre) {
    $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];
    if (!in_array($genre, $allowedGenres)) {
        http_response_code(404);
        echo "Género no válido";
        return;
    }

    $posts = Post::obtenerPublicadosPorGenero($genre);

    $genreSlug = $genre;
    include __DIR__ . '/../vista/genero.php';
}


    // ========== HELPERS LOGIN / ADMIN ==========

    private function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }

    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: login.php");
            exit;
        }
    }

    // ========== ZONA ADMIN ==========

    // Panel general admin (no es el de pendientes)
    public function adminIndex() {
        $this->requireAdmin();
        $posts = Post::obtenerTodos();
        include __DIR__ . '/../vista/admin_libros.php';
    }

    // Formulario nueva reseña
    public function crearForm() {
        $this->requireLogin();
        $errores = [];
        $postData = [];
        $currentCover = null;
        include __DIR__ . '/../vista/form_libro.php';
    }

    // Guardar nueva reseña
    public function guardarNuevo() {
        $this->requireLogin();

        $titulo    = trim($_POST['title'] ?? '');
        $autor     = trim($_POST['author'] ?? '');
        $contenido = trim($_POST['content'] ?? '');
        $genero    = trim($_POST['genre'] ?? 'general');

        $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];
        $errores = [];

        if (strlen($titulo) < 3)   $errores[] = "El título debe tener al menos 3 caracteres.";
        if (strlen($autor) < 3)    $errores[] = "El autor debe tener al menos 3 caracteres.";
        if (strlen($contenido) < 10) $errores[] = "El contenido debe tener al menos 10 caracteres.";
        if (!in_array($genero, $allowedGenres)) $errores[] = "Género no válido.";

        // Subida de portada
        $coverImagePath = null;
        if (!empty($_FILES['cover']['name'])) {
            if ($_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $mime = mime_content_type($_FILES['cover']['tmp_name']);
                $allowedMime = ['image/jpeg','image/png'];

                if (!in_array($mime, $allowedMime)) {
                    $errores[] = "Formato de imagen no permitido (JPG o PNG).";
                } elseif ($_FILES['cover']['size'] > 2 * 1024 * 1024) {
                    $errores[] = "La imagen no puede superar los 2MB.";
                } else {
                    $ext = $mime === 'image/jpeg' ? '.jpg' : '.png';
                    $nuevoNombre = uniqid('cover_', true) . $ext;
                    $destino = __DIR__ . '/../uploads/posts/' . $nuevoNombre;

                    if (move_uploaded_file($_FILES['cover']['tmp_name'], $destino)) {
                        $coverImagePath = 'uploads/posts/' . $nuevoNombre;
                    } else {
                        $errores[] = "Error al guardar la imagen.";
                    }
                }
            } else {
                $errores[] = "Error al subir la imagen.";
            }
        }

        if (!empty($errores)) {
            $postData = [
                'title'   => $titulo,
                'author'  => $autor,
                'content' => $contenido,
                'genre'   => $genero,
            ];
            $currentCover = null;
            include __DIR__ . '/../vista/form_libro.php';
            return;
        }

       // Crear slug base
$slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo), '-'));
$slug = $slugBase;
$i = 2;

// Mientras exista, añadimos -2, -3, etc.
while (Post::slugExists($slug)) {
    $slug = $slugBase . '-' . $i;
    $i++;
}


        // Crear objeto Post
        $post = new Post();
        $post->setUserId($_SESSION['user_id']);
        $post->setTitle($titulo);
        $post->setAuthor($autor);
        $post->setContent($contenido);
        $post->setGenre($genero);
        $post->setSlug($slug);
        $post->setCoverImage($coverImagePath);

        // Contar palabras y marcar como pendiente o publicado
        $texto = $contenido;
        $numeroPalabras = str_word_count(strip_tags($texto));
        $limite = 200;

        if ($numeroPalabras > $limite) {
            $post->setStatus('pendiente');
            setcookie('resenas_pendientes', '1', time() + 86400, "/");
        } else {
            $post->setStatus('publicado');
        }


        $accion = $_POST['action'] ?? 'publish'; // publish por defecto

if ($accion === 'draft') {
    // Guardar como borrador SIEMPRE
    $post->setStatus('borrador');
} else {
    // Publicar con regla de 200 palabras
    $numeroPalabras = str_word_count(strip_tags($contenido));
    $limite = 200;

    if ($numeroPalabras > $limite) {
        $post->setStatus('pendiente');
        setcookie('resenas_pendientes', '1', time() + 86400, "/");
    } else {
        $post->setStatus('publicado');
    }
}


        $post->guardar();
        $post->guardar();

if ($accion === 'draft') {
    header("Location: mis_guardadas.php");
} else {
    header("Location: mis_resenas.php");
}
exit;


        header("Location: mis_resenas.php");
        exit;
    }

    // Reseñas pendientes
    public function listarPendientes() {
        $this->requireAdmin();
        $posts = Post::obtenerPendientes();
        include __DIR__ . '/../vista/admin_pendientes.php';
    }
public function misGuardadas() {
    $this->requireLogin();
    $userId = $_SESSION['user_id'];
    $posts = Post::obtenerBorradoresPorUsuario($userId);
    include __DIR__ . '/../vista/mis_guardadas.php';
}

    public function aprobarPendiente(int $id) {
        $this->requireAdmin();
        $post = Post::obtenerPorId($id);
        if ($post) {
            $post->setStatus('publicado');
            $post->guardar();
        }
        setcookie('resenas_pendientes', '', time() - 3600, '/');
        header("Location: admin_pendientes.php");
        exit;
    }

    public function rechazarPendiente(int $id) {
        $this->requireAdmin();
        $post = Post::obtenerPorId($id);
        if ($post) {
            $post->borrar();
        }
        header("Location: admin_pendientes.php");
        exit;
    }

    // Editar reseña
    public function editarForm($id) {
        $this->requireLogin();
        $post = Post::obtenerPorId((int)$id);
        if (!$post) {
            http_response_code(404);
            echo "Reseña no encontrada";
            return;
        }
        if ($post->getUserId() !== $_SESSION['user_id']) {
            http_response_code(403);
            echo "No puedes editar reseñas de otros usuarios.";
            return;
        }
        $postData = [
            'id'      => $post->getId(),
            'title'   => $post->getTitle(),
            'author'  => $post->getAuthor(),
            'content' => $post->getContent(),
            'genre'   => $post->getGenre(),
        ];
        $currentCover = $post->getCoverImage();
        $errores = [];
        include __DIR__ . '/../vista/form_libro.php';
    }

    public function actualizar($id) {
        $this->requireLogin();
        $post = Post::obtenerPorId((int)$id);
        if (!$post) {
            http_response_code(404);
            echo "Reseña no encontrada";
            return;
        }
        if ($post->getUserId() !== $_SESSION['user_id']) {
            http_response_code(403);
            echo "No puedes editar reseñas de otros usuarios.";
            return;
        }

        $titulo    = trim($_POST['title'] ?? '');
        $autor     = trim($_POST['author'] ?? '');
        $contenido = trim($_POST['content'] ?? '');
        $genero    = trim($_POST['genre'] ?? 'general');
        $removeCover = !empty($_POST['remove_cover']);

        $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];
        $errores = [];

        if (strlen($titulo) < 3)   $errores[] = "El título debe tener al menos 3 caracteres.";
        if (strlen($autor) < 3)    $errores[] = "El autor debe tener al menos 3 caracteres.";
        if (strlen($contenido) < 10) $errores[] = "El contenido debe tener al menos 10 caracteres.";
        if (!in_array($genero, $allowedGenres)) $errores[] = "Género no válido.";

        $coverImagePath = $post->getCoverImage();

        // Quitar portada
        if ($removeCover && $coverImagePath) {
            $filePath = __DIR__ . '/../' . $coverImagePath;
            if (strpos($coverImagePath, 'uploads/posts/') === 0 && file_exists($filePath)) {
                @unlink($filePath);
            }
            $coverImagePath = null;
        }

        // Nueva portada
        if (!empty($_FILES['cover']['name'])) {
            if ($_FILES['cover']['error'] === UPLOAD_ERR_OK) {
                $mime = mime_content_type($_FILES['cover']['tmp_name']);
                $allowedMime = ['image/jpeg','image/png'];

                if (!in_array($mime, $allowedMime)) {
                    $errores[] = "Formato de imagen no permitido (JPG o PNG).";
                } elseif ($_FILES['cover']['size'] > 2 * 1024 * 1024) {
                    $errores[] = "La imagen no puede superar los 2MB.";
                } else {
                    if ($post->getCoverImage() && !$removeCover) {
                        $oldPath = __DIR__ . '/../' . $post->getCoverImage();
                        if (strpos($post->getCoverImage(), 'uploads/posts/') === 0 && file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }

                    $ext = $mime === 'image/jpeg' ? '.jpg' : '.png';
                    $nuevoNombre = uniqid('cover_', true) . $ext;
                    $destino = __DIR__ . '/../uploads/posts/' . $nuevoNombre;

                    if (move_uploaded_file($_FILES['cover']['tmp_name'], $destino)) {
                        $coverImagePath = 'uploads/posts/' . $nuevoNombre;
                    } else {
                        $errores[] = "Error al guardar la nueva imagen.";
                    }
                }
            } else {
                $errores[] = "Error al subir la nueva imagen.";
            }
        }

        if (!empty($errores)) {
            $postData = [
                'id'      => $post->getId(),
                'title'   => $titulo,
                'author'  => $autor,
                'content' => $contenido,
                'genre'   => $genero,
            ];
            $currentCover = $post->getCoverImage();
            include __DIR__ . '/../vista/form_libro.php';
            return;
        }
$slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo), '-'));
$slug = $slugBase;
$i = 2;

// Evitar duplicados, excluyendo el propio post que estamos editando
while (Post::slugExists($slug, $post->getId())) {
    $slug = $slugBase . '-' . $i;
    $i++;
}

$post->setTitle($titulo);
$post->setAuthor($autor);
$post->setContent($contenido);
$post->setGenre($genero);
$post->setSlug($slug);

$post->guardar();

if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_resenas.php");
} else {
    header("Location: mis_resenas.php");
}
exit;


    }

    public function borrar($id) {
        $this->requireLogin();
        $post = Post::obtenerPorId((int)$id);
        if (!$post) {
            http_response_code(404);
            echo "Reseña no encontrada";
            return;
        }

        $esDueno = ($post->getUserId() === $_SESSION['user_id']);
        $esAdmin = (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin');

        if (!$esDueno && !$esAdmin) {
            http_response_code(403);
            echo "No tienes permiso para borrar esta reseña.";
            return;
        }

            $post->borrar();

    // Redirigir según quién ha borrado
    if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin_resenas.php");
    } else {
        header("Location: mis_resenas.php");
    }
    exit;
}

    

    public function misResenas() {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $posts = Post::obtenerPorUsuario($userId);
        include __DIR__ . '/../vista/mis_resenas.php';
    }

    // Panel de reseñas con filtro por género
    public function adminResenas() {
        $this->requireAdmin();

        $genre = $_GET['genre'] ?? 'todos';
        $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];

        if ($genre !== 'todos' && in_array($genre, $allowedGenres)) {
            $posts = Post::obtenerPorGenero($genre);
        } else {
            $posts = Post::obtenerTodos();
            $genre = 'todos';
        }

        $selectedGenre = $genre;
        include __DIR__ . '/../vista/admin_resenas.php';
    }
}
