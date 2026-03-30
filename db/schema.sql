-- DROP DATABASE if exists ww3;
-- CREATE DATABASE ww3;
-- USE ww3; 

-- =========================
-- TABLE: section
-- =========================
CREATE TABLE section (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,

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

-- =========================
-- DONNEES MINIMALES
-- =========================
INSERT INTO section (name, slug, title)
VALUES ('Actualite', 'actualite', 'Actualite Guerre en Iran')
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO section (name, slug, title)
VALUES ('Histoire', 'histoire', 'Histoire du conflit')
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- -- =========================
-- -- INDEX (IMPORTANT pour perf SEO)
-- -- =========================

-- -- recherche rapide par slug
-- CREATE INDEX idx_section_slug ON section(slug);
-- CREATE INDEX idx_content_slug ON content(slug);

-- -- relation FK optimisée
-- CREATE INDEX idx_content_section ON content(section_id);
-- CREATE INDEX idx_image_content ON image(content_id);

-- -- tri images
-- CREATE INDEX idx_image_order ON image(display_order);
