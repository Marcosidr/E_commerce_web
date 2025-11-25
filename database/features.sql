-

USE urbanstreet_db;

-- ====================================================
-- 1. TABELA E TRIGGER DE AUDITORIA DE PREÇO (PRODUTOS)
-- ====================================================

CREATE TABLE IF NOT EXISTS auditoria_preco_produto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    preco_antigo DECIMAL(10,2),
    preco_novo DECIMAL(10,2),
    alterado_por VARCHAR(100),
    alterado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_auditoria_produto (produto_id),
    INDEX idx_auditoria_data (alterado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER $$

CREATE TRIGGER trg_auditoria_preco_produto
AFTER UPDATE ON produtos
FOR EACH ROW
BEGIN
    IF OLD.preco <> NEW.preco THEN
        INSERT INTO auditoria_preco_produto (produto_id, preco_antigo, preco_novo, alterado_por)
        VALUES (NEW.id, OLD.preco, NEW.preco, USER());
    END IF;
END$$

DELIMITER ;

-- ====================================================
-- 2. PROCEDURE PARA INSERÇÃO MASSIVA DE PRODUTOS TESTE
-- ====================================================

DELIMITER $$

CREATE PROCEDURE sp_inserir_produtos_teste(
    IN p_qtd INT,
    IN p_categoria_id INT
)
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE v_nome VARCHAR(255);
    DECLARE v_preco DECIMAL(10,2);
    DECLARE v_estoque INT;
    
    IF p_qtd <= 0 OR p_qtd > 1000 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantidade deve estar entre 1 e 1000';
    END IF;
    
    WHILE i <= p_qtd DO
        SET v_nome = CONCAT('Produto Teste ', LPAD(i, 5, '0'));
        SET v_preco = ROUND(50 + (RAND() * 450), 2);
        SET v_estoque = FLOOR(10 + (RAND() * 90));
        
        INSERT INTO produtos (
            nome,
            slug,
            descricao,
            preco,
            categoria_id,
            marca,
            estoque,
            ativo,
            destaque,
            criado_em,
            atualizado_em
        ) VALUES (
            v_nome,
            LOWER(REPLACE(v_nome, ' ', '-')),
            CONCAT('Descrição automática do ', v_nome),
            v_preco,
            p_categoria_id,
            'Marca Teste',
            v_estoque,
            1,
            IF(RAND() > 0.7, 1, 0),
            NOW(),
            NOW()
        );
        
        SET i = i + 1;
    END WHILE;
    
    SELECT CONCAT('Inseridos ', p_qtd, ' produtos com sucesso!') AS resultado;
END$$

DELIMITER ;

-- ====================================================
-- 3. ÍNDICES PARA OTIMIZAÇÃO DE CONSULTAS (NOVO ESQUEMA)
-- ====================================================

CREATE INDEX idx_produtos_categoria_ativo 
ON produtos(categoria_id, ativo, criado_em DESC);

CREATE FULLTEXT INDEX idx_produtos_nome_descricao 
ON produtos(nome, descricao);

CREATE INDEX idx_produtos_destaque 
ON produtos(destaque, ativo, criado_em DESC);

CREATE INDEX idx_produtos_marca 
ON produtos(marca);

CREATE INDEX idx_produtos_preco 
ON produtos(preco);

CREATE INDEX idx_pedidos_usuario_data 
ON pedidos(usuario_id, criado_em DESC);

CREATE INDEX idx_pedidos_status 
ON pedidos(status, criado_em DESC);

-- ====================================================
-- 4. FUNÇÃO PARA VERIFICAR DISPONIBILIDADE DE ESTOQUE
-- ====================================================

DELIMITER $$

CREATE FUNCTION fn_verificar_estoque(
    p_produto_id INT,
    p_qtd INT
)
RETURNS VARCHAR(100)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_estoque_atual INT;
    DECLARE v_resultado VARCHAR(100);
    
    SELECT estoque INTO v_estoque_atual
    FROM produtos
    WHERE id = p_produto_id AND ativo = 1;
    
    IF v_estoque_atual IS NULL THEN
        SET v_resultado = 'PRODUTO_INEXISTENTE';
    ELSEIF v_estoque_atual >= p_qtd THEN
        SET v_resultado = 'DISPONIVEL';
    ELSEIF v_estoque_atual > 0 THEN
        SET v_resultado = CONCAT('ESTOQUE_LIMITADO:', v_estoque_atual);
    ELSE
        SET v_resultado = 'INDISPONIVEL';
    END IF;
    
    RETURN v_resultado;
END$$

DELIMITER ;

-- ====================================================
-- 5. PROCEDURE ADICIONAL: ATUALIZAR ESTOQUE
-- ====================================================

DELIMITER $$

CREATE PROCEDURE sp_atualizar_estoque_produto(
    IN p_produto_id INT,
    IN p_qtd INT,
    IN p_operacao ENUM('ADICIONAR','REMOVER')
)
BEGIN
    DECLARE v_estoque_atual INT;
    
    SELECT estoque INTO v_estoque_atual
    FROM produtos
    WHERE id = p_produto_id AND ativo = 1;
    
    IF v_estoque_atual IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Produto não encontrado ou inativo';
    END IF;
    
    IF p_qtd <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantidade deve ser maior que zero';
    END IF;
    
    IF p_operacao = 'ADICIONAR' THEN
        UPDATE produtos
        SET estoque = estoque + p_qtd,
            atualizado_em = NOW()
        WHERE id = p_produto_id;
        
        SELECT CONCAT('Estoque atualizado: +', p_qtd, ' unidades') AS resultado;
    ELSEIF p_operacao = 'REMOVER' THEN
        IF v_estoque_atual < p_qtd THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Estoque insuficiente para remoção';
        END IF;
        
        UPDATE produtos
        SET estoque = estoque - p_qtd,
            atualizado_em = NOW()
        WHERE id = p_produto_id;
        
        SELECT CONCAT('Estoque atualizado: -', p_qtd, ' unidades') AS resultado;
    END IF;
END$$

DELIMITER ;

-- ====================================================
-- 6. VIEWS PARA RELATÓRIOS (NOVO ESQUEMA)
-- ====================================================

CREATE OR REPLACE VIEW vw_produtos_baixo_estoque AS
SELECT 
    p.id,
    p.nome,
    p.marca,
    c.nome AS categoria,
    p.estoque,
    p.preco,
    p.ativo
FROM produtos p
LEFT JOIN categorias c ON p.categoria_id = c.id
WHERE p.estoque < 10
ORDER BY p.estoque ASC;

CREATE OR REPLACE VIEW vw_resumo_vendas_produto AS
SELECT 
    p.id,
    p.nome,
    p.marca,
    p.preco,
    p.estoque,
    COUNT(i.id) AS total_pedidos,
    SUM(i.quantidade) AS total_quantidade_vendida,
    SUM(i.subtotal) AS total_faturado
FROM produtos p
LEFT JOIN itens_pedido i ON p.id = i.produto_id
GROUP BY p.id, p.nome, p.marca, p.preco, p.estoque
ORDER BY total_faturado DESC;


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
