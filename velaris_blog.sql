CREATE DATABASE IF NOT EXISTS velaris_blog
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE velaris_blog;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    profile_image VARCHAR(255) NULL,
    favorite_genres VARCHAR(255) NULL
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    genre VARCHAR(50) NOT NULL DEFAULT 'general',
    cover_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Opcional: marcar admin si ya existe
UPDATE users
SET role = 'admin'
WHERE username = 'admin';


ALTER TABLE posts
    ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'publicado';
    
    -- Guardados / favoritos
CREATE TABLE IF NOT EXISTS saved_posts (
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Reacciones (like/corazon)
CREATE TABLE IF NOT EXISTS reactions (
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    type ENUM('like','heart') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, post_id, type),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

   
SELECT * FROM saved_posts;
DESC saved_posts;

