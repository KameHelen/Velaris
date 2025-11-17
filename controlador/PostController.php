<?php
// controlador/PostController.php
require_once __DIR__ . '/../modelo/Post.php';

class PostController {

    // LISTADO PÚBLICO
    public function index() {
        $posts = Post::obtenerTodos();
        include __DIR__ . '/../vista/home.php';
    }

    // DETALLE LIBRO
    public function mostrar($slug) {
        $post = Post::obtenerPorSlug($slug);
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
        $posts = Post::obtenerPorGenero($genre);
        $genreSlug = $genre;
        include __DIR__ . '/../vista/genero.php';
    }

    // ===== ZONA ADMIN =====

    private function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }
    }

    public function adminIndex() {
        $this->requireLogin();
        $posts = Post::obtenerTodos();
        include __DIR__ . '/../vista/admin_libros.php';
    }

    public function crearForm() {
        $this->requireLogin();
        $errores = [];
        $postData = [];
        $currentCover = null;
        include __DIR__ . '/../vista/form_libro.php';
    }

    public function guardarNuevo() {
        $this->requireLogin();

        $titulo  = trim($_POST['title'] ?? '');
        $autor   = trim($_POST['author'] ?? '');
        $contenido = trim($_POST['content'] ?? '');
        $genero  = trim($_POST['genre'] ?? 'general');

        $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];
        $errores = [];

        if (strlen($titulo) < 3) $errores[] = "El título debe tener al menos 3 caracteres.";
        if (strlen($autor) < 3) $errores[] = "El autor debe tener al menos 3 caracteres.";
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

        // Crear slug
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo), '-'));

        $post = new Post();
        $post->setUserId($_SESSION['user_id']);
        $post->setTitle($titulo);
        $post->setAuthor($autor);
        $post->setContent($contenido);
        $post->setGenre($genero);
        $post->setSlug($slug);
        $post->setCoverImage($coverImagePath);

        $post->guardar();

        header("Location: admin_libros.php");
    }

    public function editarForm($id) {
        $this->requireLogin();
        $post = Post::obtenerPorId((int)$id);
        if (!$post) {
            http_response_code(404);
            echo "Reseña no encontrada";
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

        $titulo  = trim($_POST['title'] ?? '');
        $autor   = trim($_POST['author'] ?? '');
        $contenido = trim($_POST['content'] ?? '');
        $genero  = trim($_POST['genre'] ?? 'general');
        $removeCover = !empty($_POST['remove_cover']);

        $allowedGenres = ['fantasia','ciencia-ficcion','misterio','terror','romance','ensayo'];
        $errores = [];

        if (strlen($titulo) < 3) $errores[] = "El título debe tener al menos 3 caracteres.";
        if (strlen($autor) < 3) $errores[] = "El autor debe tener al menos 3 caracteres.";
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

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo), '-'));

        $post->setTitle($titulo);
        $post->setAuthor($autor);
        $post->setContent($contenido);
        $post->setGenre($genero);
        $post->setSlug($slug);
        $post->setCoverImage($coverImagePath);

        $post->guardar();

        header("Location: admin_libros.php");
    }

    public function borrar($id) {
        $this->requireLogin();
        $post = Post::obtenerPorId((int)$id);
        if (!$post) {
            http_response_code(404);
            echo "Reseña no encontrada";
            return;
        }
        $post->borrar();
        header("Location: admin_libros.php");
    }
}
