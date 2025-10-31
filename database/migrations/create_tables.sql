-- ===========================================
-- URBANSTREET - Estrutura do Banco de Dados
-- ===========================================

-- Criar banco se não existir
CREATE DATABASE IF NOT EXISTS urbanstreet DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE urbanstreet;

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de produtos (sem coluna de imagem no banco)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    old_price DECIMAL(10,2) NULL,
    brand VARCHAR(100),
    sku VARCHAR(50) UNIQUE,
    stock_quantity INT DEFAULT 0,
    category_id INT,
    featured TINYINT(1) DEFAULT 0,
    active TINYINT(1) DEFAULT 1,
    meta_title VARCHAR(200),
    meta_description VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Inserir categorias de exemplo
INSERT INTO categories (name, slug, description, active, sort_order) VALUES
('Tênis', 'tenis', 'Calçados urbanos autênticos', 1, 1),
('Camisetas', 'camisetas', 'Camisas e tops streetwear', 1, 2),
('Moletons', 'moletons', 'Hoodies e casacos urbanos', 1, 3),
('Calças', 'calcas', 'Jeans e calças street', 1, 4),
('Acessórios', 'acessorios', 'Bonés, relógios e mais', 1, 5)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Inserir produtos de exemplo (sem imagens no banco)
INSERT INTO products (name, slug, description, short_description, price, old_price, brand, sku, stock_quantity, category_id, featured, active) VALUES
('Tênis Urban Classic', 'tenis-urban-classic', 'Tênis urbano com design autêntico e confortável para o dia a dia.', 'Tênis urbano confortável', 299.90, 399.90, 'UrbanStreet', 'URB-TEN-001', 15, 1, 1, 1),
('Camiseta Oversized Black', 'camiseta-oversized-black', 'Camiseta oversized preta com estampa exclusiva da marca.', 'Camiseta oversized preta', 89.90, NULL, 'UrbanStreet', 'URB-CAM-002', 25, 2, 1, 1),
('Moletom Street Hood', 'moletom-street-hood', 'Moletom com capuz, perfeito para o estilo urbano.', 'Moletom urbano com capuz', 179.90, 229.90, 'UrbanStreet', 'URB-MOL-003', 12, 3, 1, 1),
('Calça Cargo Urban', 'calca-cargo-urban', 'Calça cargo resistente com múltiplos bolsos.', 'Calça cargo urbana', 159.90, NULL, 'UrbanStreet', 'URB-CAL-004', 20, 4, 0, 1),
('Boné Snapback Logo', 'bone-snapback-logo', 'Boné snapback com logo bordado da marca.', 'Boné snapback urbano', 69.90, 89.90, 'UrbanStreet', 'URB-BON-005', 30, 5, 1, 1),
('Tênis High Street', 'tenis-high-street', 'Tênis cano alto para quem busca estilo e atitude.', 'Tênis cano alto urbano', 349.90, NULL, 'UrbanStreet', 'URB-TEN-006', 8, 1, 1, 1),
('Camiseta Graphic Tee', 'camiseta-graphic-tee', 'Camiseta com estampa gráfica exclusiva.', 'Camiseta com estampa', 79.90, NULL, 'UrbanStreet', 'URB-CAM-007', 18, 2, 0, 1),
('Jaqueta Bomber', 'jaqueta-bomber', 'Jaqueta bomber urbana com detalhes únicos.', 'Jaqueta bomber street', 259.90, 329.90, 'UrbanStreet', 'URB-JAQ-008', 6, 3, 1, 1)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Atualizar IDs das categorias nos produtos caso necessário
UPDATE products SET category_id = (SELECT id FROM categories WHERE slug = 'tenis') WHERE sku IN ('URB-TEN-001', 'URB-TEN-006');
UPDATE products SET category_id = (SELECT id FROM categories WHERE slug = 'camisetas') WHERE sku IN ('URB-CAM-002', 'URB-CAM-007');
UPDATE products SET category_id = (SELECT id FROM categories WHERE slug = 'moletons') WHERE sku IN ('URB-MOL-003', 'URB-JAQ-008');
UPDATE products SET category_id = (SELECT id FROM categories WHERE slug = 'calcas') WHERE sku = 'URB-CAL-004';
UPDATE products SET category_id = (SELECT id FROM categories WHERE slug = 'acessorios') WHERE sku = 'URB-BON-005';