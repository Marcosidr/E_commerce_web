
-- BANCO EM PORTUGUÊS - URBANSTREET

CREATE DATABASE IF NOT EXISTS urbanstreet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE urbanstreet_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS itens_pedido;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;
SET FOREIGN_KEY_CHECKS = 1;

-- TABELA: categorias
CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    descricao TEXT NULL,
    ordem INT DEFAULT 0,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categorias_ativo (ativo),
    INDEX idx_categorias_ordem (ordem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA: produtos
CREATE TABLE produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT UNSIGNED NULL,
    nome VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    marca VARCHAR(120) NULL,
    descricao TEXT NULL,
    preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estoque INT NOT NULL DEFAULT 0,
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    imagem VARCHAR(255) NULL,
    criado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_produtos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_produtos_ativo (ativo),
    INDEX idx_produtos_categoria (categoria_id),
    INDEX idx_produtos_destaque (destaque),
    INDEX idx_produtos_marca (marca),
    INDEX idx_produtos_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA: usuarios
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    email_verificado_em TIMESTAMP NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(30) NULL,
    data_nascimento DATE NULL,
    genero ENUM('M','F','O') NULL,
    newsletter TINYINT(1) NOT NULL DEFAULT 0,
    sms_marketing TINYINT(1) NOT NULL DEFAULT 0,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA: pedidos
CREATE TABLE pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pendente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    criado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_pedidos_status (status),
    INDEX idx_pedidos_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA: itens_pedido
CREATE TABLE itens_pedido (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    produto_id INT UNSIGNED NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_itens_pedido_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_itens_pedido_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_itens_pedido_pedido (pedido_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEED: categorias
INSERT INTO categorias (nome, slug, descricao, ordem, ativo) VALUES
 ('Tênis', 'tenis', 'Calçados urbanos e esportivos', 1, 1),
 ('Camisetas', 'camisetas', 'Camisetas oversized e regulares', 2, 1),
 ('Moletons', 'moletons', 'Hoodies e moletom casual', 3, 1),
 ('Acessórios', 'acessorios', 'Bonés, meias e mais', 4, 1)
ON DUPLICATE KEY UPDATE nome = VALUES(nome);

-- SEED: produtos (exemplo)
INSERT INTO produtos (categoria_id, nome, slug, marca, descricao, preco, estoque, destaque, ativo, imagem) VALUES
 ((SELECT id FROM categorias WHERE slug='tenis'), 'Air Force Urban Black', 'air-force-urban-black', 'NIKE', 'Edição urbana do clássico Air Force.', 599.90, 50, 1, 1, NULL),
 ((SELECT id FROM categorias WHERE slug='tenis'), 'Dunk Low Street Edition', 'dunk-low-street-edition', 'NIKE', 'Versão street do Dunk Low.', 749.90, 50, 1, 1, NULL),
 ((SELECT id FROM categorias WHERE slug='camisetas'), 'Camiseta Oversized Preta', 'camiseta-oversized-preta', 'URBAN', 'Camiseta oversized preta premium.', 149.90, 50, 1, 1, NULL),
 ((SELECT id FROM categorias WHERE slug='moletons'), 'Moletom Oversized Cinza', 'moletom-oversized-cinza', 'URBAN', 'Moletom cinza confortável e estiloso.', 299.90, 50, 0, 1, NULL)
ON DUPLICATE KEY UPDATE nome = VALUES(nome), preco = VALUES(preco), estoque = VALUES(estoque);

-- SEED: usuário admin (senha: admin123 - gerar hash em PHP)
INSERT INTO usuarios (nome, email, senha, telefone, genero, newsletter, sms_marketing, ativo) VALUES
 ('Admin', 'admin@urbanstreet.local', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEfghiJKLmnopqr', '11999990000', 'O', 1, 0, 1)
ON DUPLICATE KEY UPDATE nome = VALUES(nome);

-- FIM DO ARQUIVO
