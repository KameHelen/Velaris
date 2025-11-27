<?php
// modelo/Post.php
require_once __DIR__ . '/Database.php';

class Post {
    private $id;
    private $userId;
    private $title;
    private $author;
    private $slug;
    private $content;
    private $genre;
    private $coverImage;
    private $createdAt;
    private $updatedAt;
    private $userName;
    private $userAvatar;
    private $status;


    public function __construct(array $data = []) {
        $this->id         = $data['id'] ?? null;
        $this->userId     = $data['user_id'] ?? null;
        $this->title      = $data['title'] ?? '';
        $this->author     = $data['author'] ?? '';
        $this->slug       = $data['slug'] ?? '';
        $this->content    = $data['content'] ?? '';
        $this->genre      = $data['genre'] ?? 'general';
        $this->coverImage = $data['cover_image'] ?? null;
        $this->createdAt  = $data['created_at'] ?? null;
        $this->updatedAt  = $data['updated_at'] ?? null;
        $this->userName   = $data['user_name'] ?? null; 
        $this->userAvatar = $data['user_avatar'] ?? null;
        $this->status = $data['status'] ?? 'publicado';

    }

    // ===== Métodos estáticos =====

    public static function obtenerTodos(): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->query("
       SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}

public static function obtenerPorSlug(string $slug): ?Post {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.slug = :slug
    ");
    $stmt->execute([':slug' => $slug]);
    $fila = $stmt->fetch();
    return $fila ? new Post($fila) : null;
}

public static function obtenerPorGenero(string $genre): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.genre = :genre
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([':genre' => $genre]);
    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}

public static function obtenerPorId(int $id): ?Post {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $fila = $stmt->fetch();
    return $fila ? new Post($fila) : null;
}

/* NUEVO: posts de un usuario concreto */
public static function obtenerPorUsuario(int $userId): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.user_id = :uid
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([':uid' => $userId]);
    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}

// Posts visibles públicamente (solo publicados)
public static function obtenerPublicados(): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->query("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'publicado'
        ORDER BY p.created_at DESC
    ");
    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}

public static function obtenerPublicadosPorGenero(string $genre): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.genre = :genre
          AND p.status = 'publicado'
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([':genre' => $genre]);
    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}

public static function obtenerPublicadoPorSlug(string $slug): ?Post {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.slug = :slug
          AND p.status = 'publicado'
    ");
    $stmt->execute([':slug' => $slug]);
    $fila = $stmt->fetch();
    return $fila ? new Post($fila) : null;
}
public static function slugExists(string $slug, ?int $excludeId = null): bool {
    $pdo = Database::getConexion();

    if ($excludeId !== null) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug AND id <> :id");
        $stmt->execute([
            ':slug' => $slug,
            ':id'   => $excludeId
        ]);
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
    }

    return (int)$stmt->fetchColumn() > 0;
}

public static function obtenerBorradoresPorUsuario(int $userId): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.user_id = :uid AND p.status = 'borrador'
        ORDER BY p.updated_at DESC
    ");
    $stmt->execute([':uid' => $userId]);

    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}

public static function contarReacciones(int $postId, string $type): int {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reactions WHERE post_id = :pid AND type = :type");
    $stmt->execute([':pid' => $postId, ':type' => $type]);
    return (int)$stmt->fetchColumn();
}
public static function usuarioReacciono(int $postId, int $userId, string $type): bool {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT 1 FROM reactions
        WHERE post_id = :pid AND user_id = :uid AND type = :type
        LIMIT 1
    ");
    $stmt->execute([':pid'=>$postId, ':uid'=>$userId, ':type'=>$type]);
    return (bool)$stmt->fetchColumn();
}
public static function toggleReaccion(int $postId, int $userId, string $type): void {
    $pdo = Database::getConexion();

    if (self::usuarioReacciono($postId, $userId, $type)) {
        $stmt = $pdo->prepare("
            DELETE FROM reactions
            WHERE post_id = :pid AND user_id = :uid AND type = :type
        ");
        $stmt->execute([':pid'=>$postId, ':uid'=>$userId, ':type'=>$type]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO reactions (post_id, user_id, type)
            VALUES (:pid, :uid, :type)
        ");
        $stmt->execute([':pid'=>$postId, ':uid'=>$userId, ':type'=>$type]);
    }
}
public static function usuarioGuardo(int $postId, int $userId): bool {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT 1 FROM saved_posts
        WHERE post_id = :pid AND user_id = :uid
        LIMIT 1
    ");
    $stmt->execute([':pid'=>$postId, ':uid'=>$userId]);
    return (bool)$stmt->fetchColumn();
}

public static function toggleGuardado(int $postId, int $userId): void {
    $pdo = Database::getConexion();

    if (self::usuarioGuardo($postId, $userId)) {
        $stmt = $pdo->prepare("
            DELETE FROM saved_posts
            WHERE post_id = :pid AND user_id = :uid
        ");
        $stmt->execute([':pid'=>$postId, ':uid'=>$userId]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO saved_posts (post_id, user_id)
            VALUES (:pid, :uid)
        ");
        $stmt->execute([':pid'=>$postId, ':uid'=>$userId]);
    }
}
public static function obtenerGuardadasPorUsuario(int $userId): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM saved_posts s
        JOIN posts p ON s.post_id = p.id
        JOIN users u ON p.user_id = u.id
        WHERE s.user_id = :uid
        ORDER BY s.created_at DESC
    ");
    $stmt->execute([':uid' => $userId]);

    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}




    // ===== Persistencia =====

    public function guardar(): bool {
        $pdo = Database::getConexion();

       if ($this->id) {
    $stmt = $pdo->prepare(
        "UPDATE posts 
         SET title = :title, author = :author, slug = :slug,
             content = :content, genre = :genre, cover_image = :cover_image,
             status = :status
         WHERE id = :id"
    );
    return $stmt->execute([
        ':title'       => $this->title,
        ':author'      => $this->author,
        ':slug'        => $this->slug,
        ':content'     => $this->content,
        ':genre'       => $this->genre,
        ':cover_image' => $this->coverImage,
        ':status'      => $this->status,
        ':id'          => $this->id
    ]);
} else {
    $stmt = $pdo->prepare(
        "INSERT INTO posts (user_id, title, author, slug, content, genre, cover_image, status)
         VALUES (:user_id, :title, :author, :slug, :content, :genre, :cover_image, :status)"
    );
    $ok = $stmt->execute([
        ':user_id'     => $this->userId,
        ':title'       => $this->title,
        ':author'      => $this->author,
        ':slug'        => $this->slug,
        ':content'     => $this->content,
        ':genre'       => $this->genre,
        ':cover_image' => $this->coverImage,
        ':status'      => $this->status
    ]);
    if ($ok) {
        $this->id = (int)$pdo->lastInsertId();
    }
    return $ok;
}

    }

    public function borrar(): bool {
        if (!$this->id) return false;

        // Borrar portada del disco
        if ($this->coverImage) {
            $filePath = __DIR__ . '/../' . $this->coverImage; // uploads/posts/...
            if (strpos($this->coverImage, 'uploads/posts/') === 0 && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }
    public static function obtenerPendientes(): array {
    $pdo = Database::getConexion();
    $stmt = $pdo->prepare("
        SELECT p.*, u.username AS user_name, u.profile_image AS user_avatar
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'pendiente'
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    $posts = [];
    while ($fila = $stmt->fetch()) {
        $posts[] = new Post($fila);
    }
    return $posts;
}
public static function contarPendientes(): int {
    $pdo = Database::getConexion();
    $stmt = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'pendiente'");
    return (int)$stmt->fetchColumn();
}


    // ===== Getters / Setters =====

    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }
    public function getTitle(): string { return $this->title; }
    public function getAuthor(): string { return $this->author; }
    public function getSlug(): string { return $this->slug; }
    public function getContent(): string { return $this->content; }
    public function getGenre(): string { return $this->genre; }
    public function getCoverImage(): ?string { return $this->coverImage; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUserName(): ?string {return $this->userName;}
    public function getUserAvatar(): string {
    if (!empty($this->userAvatar)) {
        return $this->userAvatar;
    }
    return 'img/default_avatar.png'; // fallback
}
public function getStatus(): string { return $this->status; }


    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setAuthor(string $author): void { $this->author = $author; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function setContent(string $content): void { $this->content = $content; }
    public function setGenre(string $genre): void { $this->genre = $genre; }
    public function setCoverImage(?string $coverImage): void { $this->coverImage = $coverImage; }
    public function setStatus(string $status): void { $this->status = $status; }
}
