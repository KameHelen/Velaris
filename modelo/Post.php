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
    }

    // ===== Métodos estáticos =====

    public static function obtenerTodos(): array {
        $pdo = Database::getConexion();
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        $posts = [];
        while ($fila = $stmt->fetch()) {
            $posts[] = new Post($fila);
        }
        return $posts;
    }

    public static function obtenerPorSlug(string $slug): ?Post {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = :slug");
        $stmt->execute([':slug' => $slug]);
        $fila = $stmt->fetch();
        return $fila ? new Post($fila) : null;
    }

    public static function obtenerPorGenero(string $genre): array {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE genre = :genre ORDER BY created_at DESC");
        $stmt->execute([':genre' => $genre]);
        $posts = [];
        while ($fila = $stmt->fetch()) {
            $posts[] = new Post($fila);
        }
        return $posts;
    }

    public static function obtenerPorId(int $id): ?Post {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();
        return $fila ? new Post($fila) : null;
    }

    // ===== Persistencia =====

    public function guardar(): bool {
        $pdo = Database::getConexion();

        if ($this->id) {
            $stmt = $pdo->prepare(
                "UPDATE posts 
                 SET title = :title, author = :author, slug = :slug,
                     content = :content, genre = :genre, cover_image = :cover_image
                 WHERE id = :id"
            );
            return $stmt->execute([
                ':title'       => $this->title,
                ':author'      => $this->author,
                ':slug'        => $this->slug,
                ':content'     => $this->content,
                ':genre'       => $this->genre,
                ':cover_image' => $this->coverImage,
                ':id'          => $this->id
            ]);
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO posts (user_id, title, author, slug, content, genre, cover_image)
                 VALUES (:user_id, :title, :author, :slug, :content, :genre, :cover_image)"
            );
            $ok = $stmt->execute([
                ':user_id'     => $this->userId,
                ':title'       => $this->title,
                ':author'      => $this->author,
                ':slug'        => $this->slug,
                ':content'     => $this->content,
                ':genre'       => $this->genre,
                ':cover_image' => $this->coverImage
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

    public function setUserId(int $userId): void { $this->userId = $userId; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setAuthor(string $author): void { $this->author = $author; }
    public function setSlug(string $slug): void { $this->slug = $slug; }
    public function setContent(string $content): void { $this->content = $content; }
    public function setGenre(string $genre): void { $this->genre = $genre; }
    public function setCoverImage(?string $coverImage): void { $this->coverImage = $coverImage; }
}
