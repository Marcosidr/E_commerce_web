-- ===================================================================
-- UrbanStreet - Rotinas administrativas (audit, estoque, views)
-- Compatível com MariaDB / MySQL (ajustado para suas tabelas PT-BR)
-- ===================================================================

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- -------------------------
-- 1) Tabela de auditoria de preços
-- -------------------------
DROP TABLE IF EXISTS `produto_preco_auditoria`;
CREATE TABLE `produto_preco_auditoria` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `produto_id` INT UNSIGNED NOT NULL,
  `preco_antigo` DECIMAL(10,2) NOT NULL,
  `preco_novo` DECIMAL(10,2) NOT NULL,
  `alterado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_produto_preco_audit_produto` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -------------------------
-- 2) Trigger: registra mudanças de preço em produtos
-- -------------------------
DROP TRIGGER IF EXISTS `trg_produto_preco_auditoria`;
DELIMITER $$
CREATE TRIGGER `trg_produto_preco_auditoria`
BEFORE UPDATE ON `produtos`
FOR EACH ROW
BEGIN
    -- Apenas registra se o preço realmente mudou
    IF OLD.preco IS NULL THEN
        SET OLD.preco = 0.00;
    END IF;

    IF OLD.preco <> NEW.preco THEN
        INSERT INTO produto_preco_auditoria (produto_id, preco_antigo, preco_novo)
        VALUES (OLD.id, OLD.preco, NEW.preco);
    END IF;
END$$
DELIMITER ;

-- -------------------------
-- 3) Função: verificar disponibilidade de estoque
-- -------------------------
DROP FUNCTION IF EXISTS `fn_verificar_estoque`;
DELIMITER $$
CREATE FUNCTION `fn_verificar_estoque`(pid INT, qtd INT)
RETURNS TINYINT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE disponivel INT DEFAULT 0;

    SELECT estoque INTO disponivel
    FROM produtos
    WHERE id = pid
    LIMIT 1;

    IF disponivel IS NULL THEN
        RETURN 0;
    END IF;

    IF disponivel >= qtd THEN
        RETURN 1;
    END IF;

    RETURN 0;
END$$
DELIMITER ;

-- -------------------------
-- 4) Procedure: atualizar estoque (ADD / REMOVE)
-- -------------------------
DROP PROCEDURE IF EXISTS `sp_atualizar_estoque`;
DELIMITER $$
CREATE PROCEDURE `sp_atualizar_estoque`(
    IN p_produto_id INT,
    IN p_qtd INT,
    IN p_operacao VARCHAR(10)
)
BEGIN
    DECLARE v_estoque_atual INT DEFAULT 0;

    -- Valida entradas
    IF p_produto_id IS NULL OR p_qtd IS NULL OR p_qtd < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Parâmetros inválidos';
    END IF;

    SELECT estoque INTO v_estoque_atual FROM produtos WHERE id = p_produto_id LIMIT 1;

    IF v_estoque_atual IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Produto não encontrado';
    END IF;

    IF UPPER(p_operacao) = 'ADD' THEN
        UPDATE produtos
        SET estoque = estoque + p_qtd,
            atualizado_em = CURRENT_TIMESTAMP
        WHERE id = p_produto_id;

    ELSEIF UPPER(p_operacao) = 'REMOVE' THEN
        IF v_estoque_atual < p_qtd THEN
            -- não permite estoque negativo
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Estoque insuficiente';
        END IF;

        UPDATE produtos
        SET estoque = estoque - p_qtd,
            atualizado_em = CURRENT_TIMESTAMP
        WHERE id = p_produto_id;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Operação inválida (use ADD ou REMOVE)';
    END IF;
END$$
DELIMITER ;

-- -------------------------
-- 5) Procedure: inserir produtos em massa (teste)
--    Uso: CALL sp_inserir_produtos_em_massa(50, 1);
--    -> cria N produtos de teste na categoria informada
-- -------------------------
DROP PROCEDURE IF EXISTS `sp_inserir_produtos_em_massa`;
DELIMITER $$
CREATE PROCEDURE `sp_inserir_produtos_em_massa`(
    IN p_count INT,
    IN p_categoria_id INT
)
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE v_nome VARCHAR(200);
    DECLARE v_slug VARCHAR(255);

    IF p_count IS NULL OR p_count <= 0 THEN
        LEAVE proc_end;
    END IF;

    WHILE i <= p_count DO
        SET v_nome = CONCAT('Produto Teste ', i, ' - ', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'));
        SET v_slug = CONCAT('produto-teste-', i, '-', UNIX_TIMESTAMP());

        INSERT INTO produtos
            (categoria_id, nome, slug, marca, descricao, preco, estoque, destaque, ativo, criado_em, atualizado_em)
        VALUES
            (p_categoria_id, v_nome, v_slug, 'TEST', 'Produto gerado para testes em massa', ROUND( RAND() * 200 + 10, 2 ), FLOOR(RAND() * 100), 0, 1, NOW(), NOW());

        SET i = i + 1;
    END WHILE;

    proc_end: BEGIN END;
END$$
DELIMITER ;

-- -------------------------
-- 6) View: produtos com baixo estoque
-- -------------------------
DROP VIEW IF EXISTS `vw_produtos_baixo_estoque`;
CREATE VIEW `vw_produtos_baixo_estoque` AS
SELECT 
    p.id,
    p.nome,
    p.estoque,
    p.preco,
    p.categoria_id,
    c.nome AS categoria_nome
FROM produtos p
LEFT JOIN categorias c ON c.id = p.categoria_id
WHERE p.estoque <= 10
ORDER BY p.estoque ASC;

-- -------------------------
-- 7) View: resumo de vendas por produto
-- -------------------------
DROP VIEW IF EXISTS `vw_resumo_vendas`;
CREATE VIEW `vw_resumo_vendas` AS
SELECT 
    p.id AS produto_id,
    p.nome AS produto,
    COALESCE(SUM(ip.quantidade),0) AS total_itens_vendidos,
    COALESCE(SUM(ip.subtotal),0.00) AS total_vendido,
    COALESCE(COUNT(DISTINCT ip.pedido_id),0) AS total_pedidos
FROM produtos p
LEFT JOIN itens_pedido ip ON ip.produto_id = p.id
GROUP BY p.id, p.nome;

-- -------------------------
-- 8) Sugestões de índices (aplicar se necessário)
-- -------------------------
-- ALTER TABLE produtos ADD INDEX idx_produtos_preco (preco);
-- ALTER TABLE itens_pedido ADD INDEX idx_itens_pedido_produto (produto_id);

-- ===================================================================
-- FIM
-- ===================================================================
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
