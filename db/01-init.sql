-- ========================================================================================
-- WW3: Initialisation complète de la base de données
-- ========================================================================================
-- Ce script est exécuté automatiquement au démarrage du conteneur MySQL
-- Docker utilise l'option: -v ./db:/docker-entrypoint-initdb.d

-- Création de la base de données
-- CREATE DATABASE IF NOT EXISTS ww3;
-- USE ww3;

-- ========================================================================================
-- TABLE: user
-- ========================================================================================
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================================================
-- TABLE: section
-- ========================================================================================
CREATE TABLE section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,

    user_id INT NOT NULL,

    CONSTRAINT fk_section_user
        FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,

    INDEX idx_section_name (name),
    INDEX idx_section_title (title),
    INDEX idx_section_deleted_updated (deleted_at, updated_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================================================
-- TABLE: content
-- ========================================================================================
CREATE TABLE content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,
    summary TEXT,
    content_text LONGTEXT,

    meta_title VARCHAR(255),
    slug VARCHAR(255) NOT NULL UNIQUE,
    meta_description TEXT,

    user_id INT NOT NULL,

    CONSTRAINT fk_content_user
        FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_content_section
        FOREIGN KEY (section_id)
        REFERENCES section(id)
        ON DELETE CASCADE,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,

    INDEX idx_content_section_deleted_updated (section_id, deleted_at, updated_at),
    INDEX idx_content_title (title),
    INDEX idx_content_section (section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================================================
-- TABLE: image
-- ========================================================================================
CREATE TABLE image (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,

    url VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    display_order INT DEFAULT 0,

    CONSTRAINT fk_image_content
        FOREIGN KEY (content_id)
        REFERENCES content(id)
        ON DELETE CASCADE,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,

    INDEX idx_image_content_deleted_order (content_id, deleted_at, display_order),
    INDEX idx_image_content (content_id),
    INDEX idx_image_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================================================
-- DONNÉES INITIALES
-- ========================================================================================

-- Utilisateur admin par défaut
INSERT INTO user (user_name, password)
VALUES ('admin', 'admin')
ON DUPLICATE KEY UPDATE user_name = VALUES(user_name);

-- Sections de base
INSERT INTO section (name, slug, title, user_id)
VALUES ('Actualite', 'actualite', 'Actualite Guerre en Iran', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO section (name, slug, title, user_id)
VALUES ('Histoire', 'histoire', 'Histoire du conflit', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);
