DROP DATABASE if exists ww3;
CREATE DATABASE ww3;
USE ww3; 

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
);

-- =========================
-- TABLE: section
-- =========================
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
    deleted_at DATETIME NULL
);

-- =========================
-- TABLE: content
-- =========================
CREATE TABLE content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,
    summary TEXT,
    content_text LONGTEXT,

    meta_title VARCHAR(255),    -- 
    slug VARCHAR(255) NOT NULL UNIQUE,
    meta_description TEXT,

    user_id INT NOT NULL,

    CONSTRAINT fk_content_user
        FOREIGN KEY (user_id)
        REFERENCES user(id)
        ON DELETE CASCADE,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,

    CONSTRAINT fk_content_section
        FOREIGN KEY (section_id)
        REFERENCES section(id)
        ON DELETE CASCADE
);

-- =========================
-- TABLE: image
-- =========================
CREATE TABLE image (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,

    url VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    display_order INT DEFAULT 0,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,

    CONSTRAINT fk_image_content
        FOREIGN KEY (content_id)
        REFERENCES content(id)
        ON DELETE CASCADE
);



INSERT INTO user (user_name, password)
VALUES ('admin', 'admin');

-- =========================
-- DONNEES MINIMALES
-- =========================
INSERT INTO section (name, slug, title, user_id)
VALUES ('Actualite', 'actualite', 'Actualite Guerre en Iran', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO section (name, slug, title, user_id)
VALUES ('Histoire', 'histoire', 'Histoire du conflit', 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- -- =========================
-- -- INDEX (IMPORTANT pour perf SEO)
-- -- =========================
-- INDEX (IMPORTANT pour perf SEO)
-- =========================

-- slug est deja indexe par les contraintes UNIQUE

-- recherche multicritere section + tri
CREATE INDEX idx_section_name ON section(name);
CREATE INDEX idx_section_title ON section(title);
CREATE INDEX idx_section_deleted_updated ON section(deleted_at, updated_at);
CREATE INDEX idx_content_section_deleted_updated ON content(section_id, deleted_at, updated_at);
CREATE INDEX idx_content_title ON content(title);
CREATE INDEX idx_image_content_deleted_order ON image(content_id, deleted_at, display_order);

-- relation FK optimisee
CREATE INDEX idx_content_section ON content(section_id);
CREATE INDEX idx_image_content ON image(content_id);

-- tri images
CREATE INDEX idx_image_order ON image(display_order);
