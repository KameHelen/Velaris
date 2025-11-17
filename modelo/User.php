<?php
// modelo/User.php
require_once __DIR__ . '/Database.php';

class User {
    private $id;
    private $username;
    private $passwordHash;
    private $createdAt;
    private $role;  

    public function __construct(array $data = []) {
        $this->id           = $data['id'] ?? null;
        $this->username     = $data['username'] ?? null;
        $this->passwordHash = $data['password_hash'] ?? null;
        $this->createdAt    = $data['created_at'] ?? null;
        $this->role         = $data['role'] ?? 'user'; 
    }

    public static function findByUsername(string $username): ?User {
        $pdo = Database::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
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

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function getRole(): string {
        return $this->role;
    }
}
