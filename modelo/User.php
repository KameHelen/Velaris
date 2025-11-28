<?php
// modelo/User.php
require_once __DIR__ . '/Database.php';

class User {
    private $id;
    private $username;
    private $passwordHash;
    private $createdAt;
    private $role;
    private $profileImage;
    private $favoriteGenres; 

    public function __construct(array $data = []) {
        $this->id             = $data['id'] ?? null;
        $this->username       = $data['username'] ?? null;
        $this->passwordHash   = $data['password_hash'] ?? null;
        $this->createdAt      = $data['created_at'] ?? null;
        $this->role           = $data['role'] ?? 'user';
        $this->profileImage   = $data['profile_image'] ?? null;
        $this->favoriteGenres = $data['favorite_genres'] ?? null;
    }

    public static function findByUsername(string $username): ?User {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $fila = $stmt->fetch();

        return $fila ? new User($fila) : null;
    }

    public static function findById(int $id): ?User {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch();

        return $fila ? new User($fila) : null;
    }

    public static function create(string $username, string $plainPassword): bool {
        $pdo = Database::getConexion();
        $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO users (username, password_hash, role) 
             VALUES (:username, :password_hash, 'user')"
        );

        return $stmt->execute([
            ':username'      => $username,
            ':password_hash' => $passwordHash
        ]);
    }

    public function verifyPassword(string $plainPassword): bool {
        return password_verify($plainPassword, $this->passwordHash);
    }

    public function updateProfile(): bool {
        if (!$this->id) return false;

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("
            UPDATE users
            SET profile_image = :profile_image,
                favorite_genres = :favorite_genres
            WHERE id = :id
        ");

        return $stmt->execute([
            ':profile_image'   => $this->profileImage,
            ':favorite_genres' => $this->favoriteGenres,
            ':id'              => $this->id
        ]);
    }

    public function updatePassword(string $newPlainPassword): bool {
        if (!$this->id) return false;

        $newHash = password_hash($newPlainPassword, PASSWORD_DEFAULT);

        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("
            UPDATE users
            SET password_hash = :password_hash
            WHERE id = :id
        ");

        $ok = $stmt->execute([
            ':password_hash' => $newHash,
            ':id'            => $this->id
        ]);

        if ($ok) {
            $this->passwordHash = $newHash;
        }
        return $ok;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getUsername(): ?string { return $this->username; }
    public function getRole(): string { return $this->role; }
  public function getProfileImage(): string {
    if ($this->role === 'admin') {
        return 'img/admin_avatar.png';
    }

    if (!empty($this->profileImage)) {
        return $this->profileImage;
    }

    return 'img/default_avatar.png';
}

    public function getFavoriteGenres(): ?string { return $this->favoriteGenres; }

    // Setters
    public function setProfileImage(?string $path): void { $this->profileImage = $path; }
    public function setFavoriteGenres(?string $genres): void { $this->favoriteGenres = $genres; }
}
