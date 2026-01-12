CREATE DATABASE IF NOT EXISTS db_projeto_php_2;

USE db_projeto_php_2;

CREATE TABLE IF NOT EXISTS produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descricao TEXT NOT NULL,
    categoria VARCHAR(100) NOT NULL
);
CREATE TABLE IF NOT EXISTS produto_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    url_img VARCHAR(255),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
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
    cep VARCHAR(8) NOT NULL,
    numero INT NOT NULL,
    is_adm BOOLEAN NOT NULL DEFAULT 0
);

INSERT INTO usuarios (nome, email, senha, cep, numero, is_adm)VALUES ("Administrador", "adm@gmail.com", "123456", "17512757",100,1);

CREATE TABLE IF NOT EXISTS lista_desejos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_produto INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES usuarios(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    forma_pagamento ENUM("pix", "a vista", "parcelado"),
    num_parcelas INT DEFAULT 0,
    status ENUM("Em andamento", "Finalizado", "Cancelado") DEFAULT "Em andamento",
    data_final DATETIME,
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

CREATE TABLE IF NOT EXISTS tabela_fretes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    faixa_cep CHAR(1) NOT NULL UNIQUE,
    regiao VARCHAR(50) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    prazo_dias_uteis INT NOT NULL
);
INSERT INTO tabela_fretes (faixa_cep, regiao, preco, prazo_dias_uteis) VALUES
    ('0', 'São Paulo (Capital)', 15.00, 2),
    ('1', 'São Paulo (Interior)', 20.00, 3),
    ('2', 'Rio de Janeiro / Espírito Santo', 30.00, 5),
    ('3', 'Minas Gerais', 35.00, 6),
    ('4', 'Bahia / Sergipe', 45.00, 8),
    ('5', 'Pernambuco / Alagoas / etc.', 50.00, 10),
    ('6', 'Ceará / Piauí / Maranhão / etc.', 55.00, 12),
    ('7', 'Centro-Oeste / Rondônia / etc.', 40.00, 7),
    ('8', 'Paraná / Santa Catarina', 45.00, 7),
    ('9', 'Rio Grande do Sul', 50.00, 8)
;

-- O primeiro digito do numero do cartão informa a bandeira
-- 2 e entre 51 e 55: Mastercard
-- 3 American Express
-- 4 Visa
-- 5 fora do range da mastercard: Elo
CREATE TABLE IF NOT EXISTS usuario_cartoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    token_pagamento VARCHAR(255) NOT NULL UNIQUE,
    bandeira VARCHAR(20) NOT NULL,
    last_4_digitos CHAR(4) NOT NULL,
    data_expiracao DATE NOT NULL
);


-- PROCEDURES
-- Procedure para separar uma string(array) em itens menores
    DELIMITER $$
    CREATE PROCEDURE fracionar_string(IN array_text TEXT, IN delimiter VARCHAR(1))
    BEGIN
        DECLARE position INT;
        DECLARE item_valor VARCHAR(255);
        DECLARE i INT DEFAULT 1;

        CREATE TEMPORARY TABLE IF NOT EXISTS temp_values (
            value VARCHAR(255),
            i INT
        );

        TRUNCATE TABLE temp_values;

        simple_loop: LOOP
            SET position = INSTR(array_text, delimiter);

            IF position = 0 THEN
                -- Insere o último item restante (string)
                INSERT INTO temp_values (value, i) VALUES (array_text, i); 
                LEAVE simple_loop;
            ELSE
                -- Pega o item antes do delimitador
                SET item_valor = SUBSTRING(array_text, 1, position - 1);
                INSERT INTO temp_values(value, i) VALUES (item_valor, i);

                -- Remove o item processado e o delimitador
                SET array_text = SUBSTRING(array_text, position + 1);
                SET i = i + 1;
            END IF;
        END LOOP;
    END $$
    DELIMITER ;

CALL fracionar_string("url_1;url_2", ";");

-- Procedure para separar um array de numeros em valores unicos
DELIMITER $$
CREATE PROCEDURE fracionar_numeros(IN array VARCHAR(255), IN delimiter VARCHAR(1))
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

CALL fracionar_numeros("1,2,3,5", ";");

DELIMITER $$
CREATE PROCEDURE inserir_produto (
    IN p_nome VARCHAR(200),
    IN p_preco DECIMAL(10,2),
    IN p_descricao TEXT,
    IN p_categoria VARCHAR(100),
    IN e_qtde INT,
    IN p_img_array TEXT
)
BEGIN
    DECLARE id_p INT;
    DECLARE url VARCHAR(255);
    DECLARE done BOOLEAN DEFAULT FALSE;

    DECLARE cur CURSOR FOR SELECT url_img FROM temp_url;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    CREATE TEMPORARY TABLE IF NOT EXISTS temp_url(url_img VARCHAR(255) , i INT);
    TRUNCATE TABLE temp_url;

    CALL fracionar_string(p_img_array, ";");
    INSERT INTO temp_url (url_img, i) SELECT value, i FROM temp_values;

    DROP TEMPORARY TABLE temp_values;


    INSERT INTO produtos (nome, preco, descricao, categoria) VALUES (p_nome, p_preco, p_descricao, p_categoria);
    SET id_p = LAST_INSERT_ID();

    INSERT INTO estoque (id_produto, qtde) VALUES (id_p, e_qtde);
    OPEN cur;

    simple_loop: LOOP
        FETCH cur INTO url;
        IF done THEN
            LEAVE simple_loop;
        END IF;

        INSERT INTO produto_imagens (id_produto, url_img) VALUES (id_p, url);

    END LOOP;

    CLOSE cur;

    DROP TEMPORARY TABLE temp_url;

END $$
DELIMITER ;



CALL inserir_produto("Blusa Moletom Preto com Gorro", 123.45, "Eleve o seu guarda-roupa casual com nosso moletom clássico preto com capuz. Feito com tecido macio de alta qualidade que garante conforto e durabilidade, este moletom com capuz possui um espaçoso bolso canguru e capuz com cordão ajustável. Seu design versátil o torna perfeito para um dia descontraído em casa ou para um passeio casual.", "Agasalhos - Masculino", 10, "https://i.imgur.com/cSytoSD.jpeg;https://i.imgur.com/WwKucXb.jpeg;https://i.imgur.com/cE2Dxh9.jpeg");
CALL inserir_produto("Calça Moletom com Cordão", 123.45, "Experimente a combinação perfeita de conforto e estilo com nossos joggers Classic Comfort com cordão. Projetados para um ajuste descontraído, estes joggers apresentam um tecido macio e elástico, bolsos laterais convenientes e um cordão ajustável na cintura com detalhes elegantes com pontas douradas. Ideal para relaxar ou fazer tarefas, estas calças rapidamente se tornarão sua escolha para uso casual e sem esforço.", "Agasalhos - Feminino", 10, "https://i.imgur.com/mp3rUty.jpeg;https://i.imgur.com/JQRGIc2.jpeg");
CALL inserir_produto("Calça Moletom Vermelha com Cordão", 123.45, "Experimente o máximo conforto com nossas calças de moletom vermelhas, perfeitas para sessões de treino e para relaxar em casa. Feitos com tecido macio e durável, estes joggers apresentam um cós confortável, cordão ajustável e bolsos laterais práticos para maior funcionalidade. Seu design cônico e punhos elásticos oferecem um ajuste moderno que mantém você com estilo em qualquer lugar.", "Agasalhos - Masculino", 10, "https://i.imgur.com/9LFjwpI.jpeg;https://i.imgur.com/vzrTgUR.jpeg;https://i.imgur.com/p5NdI6n.jpeg");
CALL inserir_produto("Boné Vermelho", 123.45, "Eleve o seu guarda-roupa casual com este boné de beisebol vermelho atemporal. Fabricado em tecido durável, apresenta um ajuste confortável com uma alça ajustável na parte traseira, garantindo um tamanho único. Perfeito para dias ensolarados ou para adicionar um toque esportivo ao seu look.", "Acessórios", 10, "https://i.imgur.com/cBuLvBi.jpeg;https://i.imgur.com/N1GkCIR.jpeg;https://i.imgur.com/kKc9A5p.jpeg");
CALL inserir_produto("Boné Preto", 123.45, "Eleve sua roupa casual com este boné de beisebol preto atemporal. Confeccionada em tecido respirável de alta qualidade, possui alça ajustável para ajuste perfeito. Quer você esteja correndo ou apenas fazendo algumas tarefas, este boné adiciona um toque de estilo a qualquer roupa.", "Acessórios", 10, "https://i.imgur.com/KeqG6r4.jpeg;https://i.imgur.com/xGQOw3p.jpeg");
CALL inserir_produto("Bermuda Casual Verde", 123.45, "Eleve o seu guarda-roupa casual com estes clássicos shorts chino verde-oliva. Projetados para oferecer conforto e versatilidade, eles apresentam um cós macio, bolsos práticos e um ajuste personalizado que os torna perfeitos tanto para fins de semana descontraídos quanto para ocasiões casuais elegantes. O tecido durável garante que eles resistam às suas atividades diárias, mantendo uma aparência elegante.", "Roupas de Verão - Masculino", 10, "https://i.imgur.com/UsFIvYs.jpeg;https://i.imgur.com/YIq57b6.jpeg");
CALL inserir_produto("Bermuda Casual Cinza", 123.45, "Eleve o seu guarda-roupa casual com estes clássicos shorts chino verde-oliva. Projetados para oferecer conforto e versatilidade, eles apresentam um cós macio, bolsos práticos e um ajuste personalizado que os torna perfeitos tanto para fins de semana descontraídos quanto para ocasiões casuais elegantes. O tecido durável garante que eles resistam às suas atividades diárias, mantendo uma aparência elegante.", "Roupas de Verão - Feminino", 10, "https://i.imgur.com/NLn4e7S.jpeg;https://i.imgur.com/eGOUveI.jpeg;https://i.imgur.com/UcsGO7E.jpeg");

CALL inserir_produto("Titulo", 123.45, "Descricao", "Categoria", 10, "url1;url2;etc");


-- Procedure para criar um pedido
DELIMITER $$
CREATE PROCEDURE criar_pedido (
    IN id_c INT,
    IN array_p VARCHAR(255),
    IN array_qtde VARCHAR(255),
    IN f_pgto VARCHAR(20),
    IN n_parcelas INT,
    IN status VARCHAR(20)
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE p INT;
    DECLARE q INT;
    DECLARE estoque_qtde INT;
    DECLARE id_ped INT;
    DECLARE preco_p DECIMAL(10,2);
    DECLARE total_pedido DECIMAL(10,2) DEFAULT 0;

    DECLARE cur CURSOR FOR SELECT id_p, qtde FROM temp_p JOIN temp_qtde ON (temp_p.i = temp_qtde.i);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '45000' BEGIN END;

    CREATE TEMPORARY TABLE IF NOT EXISTS temp_p(id_p INT, i INT);
    CREATE TEMPORARY TABLE IF NOT EXISTS temp_qtde(qtde INT, i INT);

    TRUNCATE TABLE temp_p;
    TRUNCATE TABLE temp_qtde;

    CALL fracionar_numeros(array_p, ",");
    INSERT INTO temp_p(id_p, i) SELECT value, i FROM temp_values;

    CALL fracionar_numeros(array_qtde, ",");
    INSERT INTO temp_qtde(qtde, i) SELECT value, i FROM temp_values;

    DROP TEMPORARY TABLE temp_values;
    


    INSERT INTO pedidos (id_cliente, data_pedido, total, forma_pagamento, num_parcelas, status) VALUES (id_c, NOW(), 0, f_pgto, n_parcelas, status);
    SET id_ped = LAST_INSERT_ID();

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
            INSERT INTO item_pedido (id_pedido, id_produto, qtde) VALUES (id_ped, p, q);

            SET estoque_qtde = estoque_qtde - q;
            UPDATE estoque SET qtde = estoque_qtde WHERE id = p;

            SELECT preco INTO preco_p FROM produtos WHERE id = p;
            SET total_pedido = total_pedido + (preco_p * q);
        END IF;
    END LOOP;

    CLOSE cur;

    UPDATE pedidos SET total = total_pedido WHERE id = id_ped;

    DROP TEMPORARY TABLE temp_p;
    DROP TEMPORARY TABLE temp_qtde;
END $$;
DELIMITER ;

CALL criar_pedido(1,"1,3,4,2,5,6","1,1,1,1,1", "a vista", 0);