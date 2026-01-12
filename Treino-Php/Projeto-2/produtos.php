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
        ) AS img_produto
    FROM produtos pro
    ORDER BY id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll();

    function extrair_valores() {

        $sql = "SELECT categoria FROM produtos GROUP BY categoria";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $categorias = $stmt->fetchAll();

        if(!$categorias) {
            error_log("Erro ao executar a consulta de categorias: " . print_r($stmt->errorInfo(), true));
            return ['categoria' => [], 'genero' => []];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto em PHP 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header class="navbar navbar-expand-lg py-1 d-flex bg-dark justify-content-center align-items-center w-100" data-bs-theme="dark">
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
    
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="px-5 py-1 fs-5">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Produtos</li>
        </ol>
    </nav>

    <main class="container-fluid row my-3 mx-auto py-3 text-center">
        
        <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarProdutos" aria-controls="sidebarProdutos">Opções de Filtragem</button>

        <div class="offcanvas-lg offcanvas-start col-md-2" tabindex="-1" id="sidebarProdutos" aria-labelledby="sidebarProdutosLabel">
            
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarProdutosLabel">Opções de Filtro:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarProdutos" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body pt-md-5 d-flex flex-column align-items-center">
                
                <h2 class="d-none d-lg-block mt-5">Filtrar por:</h2>

                <section class="my-3">
                    <h3>Categoria</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoria" id="Agasalhos">
                        <label class="form-check-label" for="Agasalhos">Agasalhos</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioDefault" id="radioDefault2">
                        <label class="form-check-label" for="radioDefault2">
                            Default checked radio
                        </label>
                    </div>
                </section>

                <section class="my-3">
                    <h3>Gênero</h3>
                </section>

                <section class="my-3">
                    <h3>Preço</h3>
                </section>
                
            </div>

        </div>
    
        <div class="container col-md-10 col-8">

            <h2 class="fs-1 mb-5 pt-5">Todos os Nossos Produtos</h2>
            <div class="container my-5 w-100 d-flex flex-wrap align-items-center justify-content-center gap-5">    
    
                <?php foreach($produtos as $p) {?>
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

        </div>
    </main>

    <footer class="container-fluid w-100 px-3 py-4 bg-dark text-white text-center">
        <p class="m-0 fs-6 fs-md-5">&copy; Copyright 2024 - mdsl1. Todos os direitos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>