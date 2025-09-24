// Create
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),
    preco DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    qtde INT NOT NULL,
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    is_adm BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS item_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    qtde INT NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);

-- Insert
INSERT INTO produtos (nome, descricao, preco) VALUES ("Produto 1", "Descrição do produto 1.", 123.45);
INSERT INTO produtos (nome, descricao, preco) VALUES ("AAAAA", "Aaaaaaaaaa", 1.99);

INSERT INTO estoque (id_produto, qtde) VALUES (1, 15);

-- Update
UPDATE produtos SET preco = 123.99 WHERE id = 1;

-- Delete
DELETE FROM produtos WHERE id <> 1;

-- Select
SELECT * FROM produtos;

SELECT * FROM produtos ORDER BY preco DESC;

SELECT * FROM produtos WHERE nome LIKE "Produto";

SELECT
    nome AS nome_produto,
    descricao AS descricao_produto
FROM produtos;

-- Situação Estoque
SELECT
    p.id,
    p.nome,
    e.qtde,
    p.preco,
    (e.qtde * p.preco) AS subtotal
FROM produtos p
JOIN estoque e ON p.id = e.id_produto
ORDER BY subtotal DESC;

-- Ver pedidos
SELECT
    ped.id,
    pro.nome,
    ip.qtde,
    (ip.qtde * pro.preco) AS subtotal,
    ped.total AS total_pedido
FROM produtos pro
JOIN item_pedido ip ON ip.id_produto = pro.id
JOIN pedidos ped ON ip.id_pedido = ped.id;

-- Ver produtos mais vendidos
SELECT
    pro.nome AS Nome,
    pro.descricao AS Descricao,
    pro.preco AS Preco,
    SUM(ip.qtde) AS qtde_vendida
FROM item_pedido ip
JOIN produtos pro ON ip.id_produto = pro.id
GROUP BY pro.id
ORDER BY qtde_vendida DESC
LIMIT 6;

-- Ver produtos com menor preco
SELECT * FROM produtos ORDER BY preco LIMIT 6;

-- Ver clientes que mais compram
SELECT
    cli.id,
    cli.nome,
    SUM(ped.total) AS total_gasto
FROM usuarios cli
JOIN pedidos ped ON cli.id = ped.id_cliente
GROUP BY cli.id
ORDER BY total_gasto DESC;


-- Procedure pra inserir 1 produto
DELIMITER $$
CREATE PROCEDURE inserir_produto (
    IN p_nome VARCHAR(100),
    IN p_descricao VARCHAR(255),
    IN p_preco DECIMAL(10,2),
    IN e_qtde INT
)
BEGIN
    INSERT INTO produtos(nome, descricao, preco) VALUES (p_nome, p_descricao, p_preco);
    INSERT INTO estoque (id_produto, qtde) VALUES (LAST_INSERT_ID(), e_qtde);
END $$
DELIMITER ;

CALL inserir_produto("Produto 3","Descrição do produto 3", 123.45, 0);


-- Procedure para inserir 100 produtos
DELIMITER $$
CREATE PROCEDURE inserir_100_produtos()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE p DECIMAL(10.2);
    DECLARE q INT;
    DECLARE n VARCHAR(100);
    DECLARE d VARCHAR(255);

    WHILE i <= 100 DO
        -- Nome e Descrição
        SET n = CONCAT("Produto ", i);
        SET d = CONCAT("Descrição do produto ", i);

        -- Preço
        SET p = 50 + (RAND() * (599.99 - 50.00));
        -- Qtde
        SET q = FLOOR(RAND() * 51);

        CALL inserir_produto(n, d, p, q);

        SET i = i + 1;
    END WHILE;
END $$
DELIMITER ;

CALL inserir_100_produtos();


-- Procedure para separar uma string(array) em itens menores
DELIMITER $$
CREATE PROCEDURE fracionar_string(IN array VARCHAR(255), IN delimiter VARCHAR(1))
BEGIN
    DECLARE position INT;
    DECLARE num VARCHAR(255);
    DECLARE i INT DEFAULT 1;

    CREATE TEMPORARY TABLE IF NOT EXISTS temp_values (
        value INT,
        i INT
    );

    TRUNCATE TABLE temp_values;

    simple_loop: LOOP
        SET position = INSTR(array, delimiter);

        IF position = 0 THEN
            INSERT INTO temp_values (value, i) VALUES (CAST(array AS UNSIGNED), i);
            LEAVE simple_loop;
        ELSE
            SET num = SUBSTRING(array, 1, position - 1);
            INSERT INTO temp_values(value, i) VALUES (CAST(num AS UNSIGNED), i);

            SET array = SUBSTRING(array, position + 1);
            SET i = i + 1;
        END IF;
    END LOOP;
END $$
DELIMITER ;

CALL fracionar_string("1,2,3,5", ";");


-- Procedure para criar um pedido
DELIMITER $$
CREATE PROCEDURE criar_pedido (
    IN id_c INT,
    IN array_p VARCHAR(255),
    IN array_qtde VARCHAR(255)
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE p INT;
    DECLARE q INT;
    DECLARE estoque_qtde INT;
    DECLARE pedido_add INT;
    DECLARE preco_p DECIMAL(10,2);
    DECLARE total_pedido DECIMAL(10,2) DEFAULT 0;

    DECLARE cur CURSOR FOR SELECT id_p, qtde FROM temp_p JOIN temp_qtde ON (temp_p.i = temp_qtde.i);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '45000' BEGIN END;

    CREATE TEMPORARY TABLE IF NOT EXISTS temp_p(id_p INT, i INT);
    CREATE TEMPORARY TABLE IF NOT EXISTS temp_qtde(qtde INT, i INT);

    TRUNCATE TABLE temp_p;
    TRUNCATE TABLE temp_qtde;

    CALL fracionar_string(array_p, ",");
    INSERT INTO temp_p(id_p, i) SELECT value, i FROM temp_values;

    CALL fracionar_string(array_qtde, ",");
    INSERT INTO temp_qtde(qtde, i) SELECT value, i FROM temp_values;

    DROP TEMPORARY TABLE temp_values;

    INSERT INTO pedidos (id_cliente, data_pedido, total) VALUES (id_c, NOW(), 0);
    SET pedido_add = LAST_INSERT_ID();

    OPEN cur;

    simple_loop: LOOP
        FETCH cur INTO p, q;
        IF done THEN
            LEAVE simple_loop;
        END IF;

        SELECT qtde INTO estoque_qtde FROM estoque WHERE id = p;
        IF (estoque_qtde < q) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = CONCAT("Erro: O produto com ID ", p, " não tem estoque suficiente.");
        ELSE
            INSERT INTO item_pedido (id_pedido, id_produto, qtde) VALUES (pedido_add, p, q);

            SET estoque_qtde = estoque_qtde - q;
            UPDATE estoque SET qtde = estoque_qtde WHERE id = p;

            SELECT preco INTO preco_p FROM produtos WHERE id = p;
            SET total_pedido = total_pedido + (preco_p * q);
        END IF;
    END LOOP;

    CLOSE cur;

    UPDATE pedidos SET total = total_pedido WHERE id = pedido_add;

    DROP TEMPORARY TABLE temp_p;
    DROP TEMPORARY TABLE temp_qtde;
END $$;
DELIMITER ;

CALL criar_pedido(1,"1,2,3,4,5","5,3,12,8,2");
