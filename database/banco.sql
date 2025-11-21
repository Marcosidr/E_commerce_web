
-- 1) CRIAR DATABASE (ajuste se já existir)
CREATE DATABASE IF NOT EXISTS urbanstreet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE urbanstreet_db;

-- 2) REMOVER TABELAS (APENAS SE PRECISAR REINICIAR)
-- SET FOREIGN_KEY_CHECKS = 0;
-- DROP TABLE IF EXISTS products;
-- DROP TABLE IF EXISTS categories;
-- DROP TABLE IF EXISTS users;
-- SET FOREIGN_KEY_CHECKS = 1;

-- 3) TABELA: categories
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    description TEXT NULL,
    sort_order INT DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categories_active (active),
    INDEX idx_categories_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) TABELA: products
CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    brand VARCHAR(120) NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stock_quantity INT NOT NULL DEFAULT 0,
    featured TINYINT(1) NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_products_active (active),
    INDEX idx_products_category (category_id),
    INDEX idx_products_featured (featured),
    INDEX idx_products_brand (brand),
    INDEX idx_products_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5) TABELA: users
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NULL,
    birth_date DATE NULL,
    gender ENUM('M','F','O') NULL,
    newsletter TINYINT(1) NOT NULL DEFAULT 0,
    sms_marketing TINYINT(1) NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6) SEED: categories
INSERT INTO categories (name, slug, description, sort_order) VALUES
 ('Tênis', 'tenis', 'Calçados urbanos e esportivos', 1),
 ('Camisetas', 'camisetas', 'Camisetas oversized e regulares', 2),
 ('Moletons', 'moletons', 'Hoodies e moletom casual', 3),
 ('Acessórios', 'acessorios', 'Bonés, meias e mais', 4)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- 7) SEED: products (exemplo)
INSERT INTO products (category_id, name, slug, brand, description, price, stock_quantity, featured, active, image) VALUES
 ((SELECT id FROM categories WHERE slug='tenis'), 'Air Force Urban Black', 'air-force-urban-black', 'NIKE', 'Edição urbana do clássico Air Force.', 599.90, 50, 1, 1, NULL),
 ((SELECT id FROM categories WHERE slug='tenis'), 'Dunk Low Street Edition', 'dunk-low-street-edition', 'NIKE', 'Versão street do Dunk Low.', 749.90, 50, 1, 1, NULL),
 ((SELECT id FROM categories WHERE slug='camisetas'), 'Oversized Tee Black', 'oversized-tee-black', 'URBAN', 'Camiseta oversized preta premium.', 149.90, 50, 1, 1, NULL),
 ((SELECT id FROM categories WHERE slug='moletons'), 'Hoodie Oversized Gray', 'hoodie-oversized-gray', 'URBAN', 'Moletom cinza confortável e estiloso.', 299.90, 50, 0, 1, NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price), stock_quantity = VALUES(stock_quantity);

-- 8) SEED: admin user (senha: admin123 - gerar hash PHP)
-- Para segurança NUNCA usar senha plain text. Aqui apenas exemplo.
INSERT INTO users (name, email, password, phone, gender, newsletter, sms_marketing, active) VALUES
 ('Admin', 'admin@urbanstreet.local', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEfghiJKLmnopqr', '11999990000', 'O', 1, 0, 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- A senha acima é PLACEHOLDER. Gere uma real com: 
-- PHP: password_hash('admin123', PASSWORD_BCRYPT);

-- 9) VIEWS / AJUSTES FUTUROS (opcionais)
-- CREATE INDEX idx_products_price ON products(price);
-- CREATE INDEX idx_users_email_active ON users(email, active);

-- 10) VERIFICAÇÕES RÁPIDAS
-- SELECT COUNT(*) FROM categories;
-- SELECT COUNT(*) FROM products;
-- SELECT COUNT(*) FROM users;

-- FIM DO ARQUIVO
