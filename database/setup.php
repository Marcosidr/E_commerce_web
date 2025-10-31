<?php
/**
 * Script para verificar e criar tabelas do banco de dados
 * Execute via: php database/setup.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Configurações do banco
$config = require_once __DIR__ . '/../config/database.php';

try {
    // Conectar sem especificar banco para criá-lo se necessário
    $pdo = new PDO(
        "mysql:host={$config['host']};charset={$config['charset']}", 
        $config['username'], 
        $config['password'],
        $config['options']
    );
    
    echo "✅ Conexão com MySQL estabelecida\n";
    
    // Criar banco se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['database']} DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Banco de dados '{$config['database']}' verificado/criado\n";
    
    // Usar o banco
    $pdo->exec("USE {$config['database']}");
    
    // Verificar se as tabelas existem
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('categories', $tables)) {
        echo "⚠️  Tabela 'categories' não encontrada. Criando...\n";
        $pdo->exec("
            CREATE TABLE categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(100) NOT NULL UNIQUE,
                description TEXT,
                active TINYINT(1) DEFAULT 1,
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        echo "✅ Tabela 'categories' criada\n";
    }
    
    if (!in_array('products', $tables)) {
        echo "⚠️  Tabela 'products' não encontrada. Criando...\n";
        $pdo->exec("
            CREATE TABLE products (
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
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
            )
        ");
        echo "✅ Tabela 'products' criada\n";
    }
    
    // Verificar se há dados nas tabelas
    $categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    if ($categoryCount == 0) {
        echo "📦 Inserindo categorias de exemplo...\n";
        $pdo->exec("
            INSERT INTO categories (name, slug, description, active, sort_order) VALUES
            ('Tênis', 'tenis', 'Calçados urbanos autênticos', 1, 1),
            ('Camisetas', 'camisetas', 'Camisas e tops streetwear', 1, 2),
            ('Moletons', 'moletons', 'Hoodies e casacos urbanos', 1, 3),
            ('Calças', 'calcas', 'Jeans e calças street', 1, 4),
            ('Acessórios', 'acessorios', 'Bonés, relógios e mais', 1, 5)
        ");
        echo "✅ Categorias inseridas\n";
    }
    
    if ($productCount == 0) {
        echo "📦 Inserindo produtos de exemplo...\n";
        $pdo->exec("
            INSERT INTO products (name, slug, description, short_description, price, old_price, brand, sku, stock_quantity, category_id, featured, active) VALUES
            ('Tênis Urban Classic', 'tenis-urban-classic', 'Tênis urbano com design autêntico e confortável para o dia a dia.', 'Tênis urbano confortável', 299.90, 399.90, 'UrbanStreet', 'URB-TEN-001', 15, 1, 1, 1),
            ('Camiseta Oversized Black', 'camiseta-oversized-black', 'Camiseta oversized preta com estampa exclusiva da marca.', 'Camiseta oversized preta', 89.90, NULL, 'UrbanStreet', 'URB-CAM-002', 25, 2, 1, 1),
            ('Moletom Street Hood', 'moletom-street-hood', 'Moletom com capuz, perfeito para o estilo urbano.', 'Moletom urbano com capuz', 179.90, 229.90, 'UrbanStreet', 'URB-MOL-003', 12, 3, 1, 1),
            ('Calça Cargo Urban', 'calca-cargo-urban', 'Calça cargo resistente com múltiplos bolsos.', 'Calça cargo urbana', 159.90, NULL, 'UrbanStreet', 'URB-CAL-004', 20, 4, 0, 1),
            ('Boné Snapback Logo', 'bone-snapback-logo', 'Boné snapback com logo bordado da marca.', 'Boné snapback urbano', 69.90, 89.90, 'UrbanStreet', 'URB-BON-005', 30, 5, 1, 1),
            ('Tênis High Street', 'tenis-high-street', 'Tênis cano alto para quem busca estilo e atitude.', 'Tênis cano alto urbano', 349.90, NULL, 'UrbanStreet', 'URB-TEN-006', 8, 1, 1, 1),
            ('Camiseta Graphic Tee', 'camiseta-graphic-tee', 'Camiseta com estampa gráfica exclusiva.', 'Camiseta com estampa', 79.90, NULL, 'UrbanStreet', 'URB-CAM-007', 18, 2, 0, 1),
            ('Jaqueta Bomber', 'jaqueta-bomber', 'Jaqueta bomber urbana com detalhes únicos.', 'Jaqueta bomber street', 259.90, 329.90, 'UrbanStreet', 'URB-JAQ-008', 6, 3, 1, 1)
        ");
        echo "✅ Produtos inseridos\n";
    }
    
    echo "\n🎉 Setup do banco concluído com sucesso!\n";
    echo "📊 Categorias: {$categoryCount}\n";
    echo "📦 Produtos: {$productCount}\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit(1);
}