<?php 
    session_start();
    require_once "backend/conexao_db.php";

    $limite_caracteres = 50;

    $sql = "SELECT
                pro.id,
                pro.nome,
                pro.descricao,
                pro.preco,
                (
                    SELECT p_img.url_img 
                    FROM produto_imagens AS p_img 
                    WHERE p_img.id_produto = pro.id
                    LIMIT 1    
                ) AS img_produto,
                SUM(ip.qtde) AS qtde_vendida
            FROM item_pedido ip
            JOIN produtos pro ON ip.id_produto = pro.id
            GROUP BY pro.id
            ORDER BY qtde_vendida DESC
            LIMIT 6";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $mais_vendidos = $stmt->fetchAll();

    $sql = "SELECT 
                pro.id,
                pro.nome,
                pro.descricao,
                pro.preco,
                (
                    SELECT p_img.url_img 
                    FROM produto_imagens AS p_img 
                    WHERE p_img.id_produto = pro.id
                    LIMIT 1    
                ) AS img_produto
            FROM produtos pro
            ORDER BY preco
            LIMIT 6";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $menor_preco = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto em PHP 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    
    <header class="navbar navbar-expand-lg py-2 px-3 d-flex bg-dark justify-content-center align-items-center w-100" data-bs-theme="dark">
        <div class="container-fluid m-auto">
            
            <a class="navbar-brand text-white d-flex align-items-center fs-3 me-md-5 pe-md-5" href="#"><div id="logo" class="d-flex align-items-center justify-content-center bg-dark text-white fs-5" style="width: 4rem; height: 4rem; border: 2px solid #3da1ff; border-radius: 50%; margin-right: 2rem;">1</div> Projeto em PHP 1</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <nav class="collapse navbar-collapse align-items-baseline justify-content-end" id="navbarNav">
                <ul class="navbar-nav nav-underline fs-4 text-white">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="produtos.php">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="carrinho.php">Carrinho</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="sobre.php">Sobre</a>
                    </li>
                    <li class="nav-item">

                        <?php if(!isset($_SESSION["id_cli"])) { ?>
                            <a class="nav-link text-white" href="login.php">Entrar</a>
                        <?php } else { ?>
                            <a class="nav-link text-white" href="backend/logoff.php">Sair</a>
                        <?php } ?>

                    </li>
                </ul>
            </nav>
            
        </div>
    </header>

    <main class="container w-75 my-3 mx-auto py-5 text-center">

        <h2 class="fs-1 mb-5 pt-5">Os Mais Vendidos</h2>
        <div class="container my-5 w-100 d-flex flex-wrap align-items-center justify-content-center gap-5">    

            <?php foreach($mais_vendidos as $p) {?>
                <div class="card text-center" style="width: 22rem; height: 38rem;">
                    <img src="<?= $p["img_produto"]; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $p["nome"]; ?></h5>
                        <p class="card-text"><?php
                            if(mb_strlen($p["descricao"], "UTF-8") > $limite_caracteres) {
                                $descricao = mb_substr($p["descricao"],0,$limite_caracteres,"UTF-8") . "...";
                            }
                            else {
                                $descricao = $p["descricao"];
                            }
                            echo $descricao;
                        ?></p>
                        <p class="card-text">R$ <?= $p["preco"]; ?></p>
                        <?php $pode_comprar = (!isset($_SESSION["id_cli"])) ? "#" : "pagina_produto.php?id=" . $p["id"]; ?>
                        <a href="<?= $pode_comprar ?>" class="btn btn-primary">Ver Produto</a>
                    </div>
                </div>
            <?php } ?>

        </div>

        <h2 class="fs-1 my-5 pt-5">Os Melhores Custo-Benefício</h2>
        <div class="container my-5 w-100 d-flex flex-wrap align-items-center justify-content-center gap-5">    

            <?php foreach($menor_preco as $p) {?>
                <div class="card text-center" style="width: 22rem; height: 38rem;">
                    <img src="<?= $p["img_produto"]; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $p["nome"]; ?></h5>
                        <p class="card-text"><?php
                            if(mb_strlen($p["descricao"], "UTF-8") > $limite_caracteres) {
                                $descricao = mb_substr($p["descricao"],0,$limite_caracteres,"UTF-8") . "...";
                            }
                            else {
                                $descricao = $p["descricao"];
                            }
                            echo $descricao;
                        ?></p>
                        <p class="card-text">R$ <?= $p["preco"]; ?></p>
                        <?php $pode_comprar = (!isset($_SESSION["id_cli"])) ? "#" : "pagina_produto.php?id=" . $p["id"]; ?>
                        <a href="<?= $pode_comprar ?>" class="btn btn-primary">Ver Produto</a>
                    </div>
                </div>
            <?php } ?>

        </div>
    </main>

    <footer class="container-fluid w-100 px-3 py-4 bg-dark text-white text-center">
        <p class="m-0 fs-6 fs-md-5">&copy; Copyright 2024 - mdsl1. Todos os direitos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>