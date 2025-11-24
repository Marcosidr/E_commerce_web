-

USE urbanstreet_db;

-- ====================================================
-- 1. TRIGGER PARA AUDITORIA DE ALTERAÇÃO DE PREÇO
-- ====================================================

-- Tabela de auditoria
CREATE TABLE IF NOT EXISTS product_price_audit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    old_price DECIMAL(10,2),
    new_price DECIMAL(10,2),
    changed_by VARCHAR(100),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product (product_id),
    INDEX idx_date (changed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trigger para registrar alterações de preço
DELIMITER $$

CREATE TRIGGER trg_product_price_audit
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    IF OLD.price != NEW.price THEN
        INSERT INTO product_price_audit (product_id, old_price, new_price, changed_by)
        VALUES (NEW.id, OLD.price, NEW.price, USER());
    END IF;
END$$

DELIMITER ;

-- ====================================================
-- 2. PROCEDURE PARA INSERÇÃO MASSIVA DE DADOS
-- ====================================================

DELIMITER $$

CREATE PROCEDURE sp_insert_bulk_products(
    IN p_quantity INT,
    IN p_category_id INT
)
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE v_name VARCHAR(255);
    DECLARE v_price DECIMAL(10,2);
    DECLARE v_stock INT;
    
    -- Validação
    IF p_quantity <= 0 OR p_quantity > 1000 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantidade deve estar entre 1 e 1000';
    END IF;
    
    -- Loop para inserção
    WHILE i <= p_quantity DO
        SET v_name = CONCAT('Produto Teste ', LPAD(i, 5, '0'));
        SET v_price = ROUND(50 + (RAND() * 450), 2); -- Entre R$50 e R$500
        SET v_stock = FLOOR(10 + (RAND() * 90)); -- Entre 10 e 100
        
        INSERT INTO products (
            name, 
            slug, 
            description, 
            price, 
            category_id, 
            brand, 
            stock_quantity, 
            active,
            featured,
            created_at,
            updated_at
        )
        VALUES (
            v_name,
            LOWER(REPLACE(v_name, ' ', '-')),
            CONCAT('Descrição automática do ', v_name),
            v_price,
            p_category_id,
            'Marca Teste',
            v_stock,
            1,
            IF(RAND() > 0.7, 1, 0), -- 30% de chance de ser destaque
            NOW(),
            NOW()
        );
        
        SET i = i + 1;
    END WHILE;
    
    SELECT CONCAT('Inseridos ', p_quantity, ' produtos com sucesso!') AS resultado;
END$$

DELIMITER ;

-- ====================================================
-- 3. ÍNDICES PARA OTIMIZAÇÃO DE CONSULTAS
-- ====================================================

-- Índice composto para busca de produtos ativos por categoria
CREATE INDEX idx_products_category_active 
ON products(category_id, active, created_at DESC);

-- Índice para busca por nome (full-text search)
CREATE FULLTEXT INDEX idx_products_name_description 
ON products(name, description);

-- Índice para produtos em destaque
CREATE INDEX idx_products_featured 
ON products(featured, active, created_at DESC);

-- Índice para busca por marca
CREATE INDEX idx_products_brand 
ON products(brand);

-- Índice para busca por preço
CREATE INDEX idx_products_price 
ON products(price);

-- Índice composto para pedidos por usuário e data
CREATE INDEX idx_orders_user_date 
ON orders(user_id, created_at DESC);

-- Índice para status de pedidos
CREATE INDEX idx_orders_status 
ON orders(status, created_at DESC);

-- ====================================================
-- 4. FUNÇÃO PARA VERIFICAR DISPONIBILIDADE DE ESTOQUE
-- ====================================================

DELIMITER $$

CREATE FUNCTION fn_check_stock_availability(
    p_product_id INT,
    p_quantity INT
)
RETURNS VARCHAR(100)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_current_stock INT;
    DECLARE v_result VARCHAR(100);
    
    -- Buscar estoque atual
    SELECT stock_quantity INTO v_current_stock
    FROM products
    WHERE id = p_product_id AND active = 1;
    
    -- Verificar se produto existe
    IF v_current_stock IS NULL THEN
        SET v_result = 'PRODUTO_INEXISTENTE';
    -- Verificar disponibilidade
    ELSEIF v_current_stock >= p_quantity THEN
        SET v_result = 'DISPONIVEL';
    ELSEIF v_current_stock > 0 THEN
        SET v_result = CONCAT('ESTOQUE_LIMITADO:', v_current_stock);
    ELSE
        SET v_result = 'INDISPONIVEL';
    END IF;
    
    RETURN v_result;
END$$

DELIMITER ;

-- ====================================================
-- 5. PROCEDURE ADICIONAL: ATUALIZAR ESTOQUE
-- ====================================================

DELIMITER $$

CREATE PROCEDURE sp_update_product_stock(
    IN p_product_id INT,
    IN p_quantity INT,
    IN p_operation ENUM('ADD', 'REMOVE')
)
BEGIN
    DECLARE v_current_stock INT;
    
    -- Buscar estoque atual
    SELECT stock_quantity INTO v_current_stock
    FROM products
    WHERE id = p_product_id AND active = 1;
    
    -- Validações
    IF v_current_stock IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produto não encontrado ou inativo';
    END IF;
    
    IF p_quantity <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantidade deve ser maior que zero';
    END IF;
    
    -- Atualizar estoque
    IF p_operation = 'ADD' THEN
        UPDATE products 
        SET stock_quantity = stock_quantity + p_quantity,
            updated_at = NOW()
        WHERE id = p_product_id;
        
        SELECT CONCAT('Estoque atualizado: +', p_quantity, ' unidades') AS resultado;
        
    ELSEIF p_operation = 'REMOVE' THEN
        IF v_current_stock < p_quantity THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Estoque insuficiente para remoção';
        END IF;
        
        UPDATE products 
        SET stock_quantity = stock_quantity - p_quantity,
            updated_at = NOW()
        WHERE id = p_product_id;
        
        SELECT CONCAT('Estoque atualizado: -', p_quantity, ' unidades') AS resultado;
    END IF;
END$$

DELIMITER ;

-- ====================================================
-- 6. VIEW PARA RELATÓRIOS
-- ====================================================

-- View de produtos com baixo estoque
CREATE OR REPLACE VIEW vw_low_stock_products AS
SELECT 
    p.id,
    p.name,
    p.brand,
    c.name AS category,
    p.stock_quantity,
    p.price,
    p.active
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
WHERE p.stock_quantity < 10
ORDER BY p.stock_quantity ASC;

-- View de produtos mais vendidos (simulada - requer tabela de pedidos completa)
CREATE OR REPLACE VIEW vw_product_sales_summary AS
SELECT 
    p.id,
    p.name,
    p.brand,
    p.price,
    p.stock_quantity,
    COUNT(oi.id) AS total_orders,
    SUM(oi.quantity) AS total_quantity_sold,
    SUM(oi.price * oi.quantity) AS total_revenue
FROM products p
LEFT JOIN order_items oi ON p.id = oi.product_id
GROUP BY p.id, p.name, p.brand, p.price, p.stock_quantity
ORDER BY total_revenue DESC;

-- ====================================================
-- EXEMPLOS DE USO
-- ====================================================

/*
-- Testar trigger de auditoria:
UPDATE products SET price = 299.90 WHERE id = 1;
SELECT * FROM product_price_audit ORDER BY changed_at DESC LIMIT 10;

-- Usar procedure de inserção massiva:
CALL sp_insert_bulk_products(50, 1);

-- Verificar disponibilidade de estoque:
SELECT fn_check_stock_availability(1, 5) AS disponibilidade;
SELECT fn_check_stock_availability(999, 5) AS disponibilidade;

-- Usar procedure de atualizar estoque:
CALL sp_update_product_stock(1, 20, 'ADD');
CALL sp_update_product_stock(1, 5, 'REMOVE');

-- Verificar produtos com baixo estoque:
SELECT * FROM vw_low_stock_products;

-- Ver resumo de vendas:
SELECT * FROM vw_product_sales_summary LIMIT 20;

-- Testar performance com índices:
EXPLAIN SELECT * FROM products WHERE category_id = 1 AND active = 1 ORDER BY created_at DESC LIMIT 10;
*/
