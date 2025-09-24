<?php
    session_start();
    require_once "backend/conexao_db.php";

    if(!isset($_SESSION["id_cli"])) {
        header("Location: index.php");
    }

    $id = intval(htmlspecialchars($_GET["id"]));
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $produto = $stmt->fetch();
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
    <header class="navbar navbar-expand-lg bg-dark text-white px-3 py-2 d-flex justify-content-between">

        <div id="logoContainer" class="container w-50 d-flex align-items-center justify-content-start">
            <div id="logo" class="d-flex align-items-center justify-content-center bg-dark text-white fs-5" style="width: 4rem; height: 4rem; border: 2px solid #3da1ff; border-radius: 50%; margin-right: 2rem;">1</div>
            <h1>Projeto em PHP 1</h1>
        </div>

        <nav class="container w-75 d-flex align-items-baseline justify-content-end">
            <ul class="navbar-nav d-flex flex-row gap-4 fs-4">
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
    </header>

    <a href="index.php" class="btn btn-primary mt-3 ms-3">Voltar</a>

    <main class="container-fluid w-100 d-flex align-items-center my-3 py-5 text-center">
        <div class="container col-8 d-flex flex-column align-items-center">

            <h1 class="fs-1 mb-5 pt-4"><?= $produto["nome"]; ?></h1>

            <div id="carouselItem" class="carousel slide w-75">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselItem" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselItem" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselItem" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img src="https://i.pinimg.com/1200x/15/2c/2c/152c2c00f26647f91086a8899dec98d1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/1e/df/e5/1edfe5697ef4dd831b61887866706427.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/8b/a7/82/8ba7825ee295e8f711552bd694b6f686.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselItem" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselItem" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <h2 class="my-4">Sobre o Produto</h2>
            <p class="fs-3"><?= $produto["descricao"]; ?></p>

        </div>

        <div class="container col-4 text-center me-5 pe-5">

            <p class="fs-4">Em até 12x de R$ <?= number_format(($produto["preco"]/12), 2 , ",", ".") ?> sem juros</p>
            <p class="fs-3">Ou R$ <?= number_format(($produto["preco"]), 2 , ",", ".") ?> à vista</p>

            <form class="d-flex flex-column align-items-center">

                <input type="hidden" name="id" id="id" value="<?= $produto["id"]; ?>">
                <input type="hidden" name="preco" id="preco" value="<?= $produto["preco"]; ?>">
                
                <div id="qtdeItensControl" class="mt-5 d-flex w-auto align-items-center justify-content-center gap-2">
                    <button type="button" id="rmvItem" class="btn btn-primary">-</button>
                    <input type="text" readonly name="qtdeItem" id="qtdeItem" value="1" class="text-center w-25">
                    <button type="button" id="addItem" class="btn btn-primary">+</button>
                </div>
                
                <p class="mt-4 mb-1 fs-4 fw-bold">Total a Pagar:</p>
                <p class="mt-1 fs-3 fw-bold" id="priceItem">R$ <?= number_format($produto["preco"], 2, ",", ".") ?></p>
    
                <button type="button" onclick="comprarAgora()" class="btn btn-primary w-50 mt-2">Comprar Agora</button>
                <button type="button" onclick="addToCarrinho()" class="btn btn-primary w-50 mt-3">Adicionar ao Carrinho</button>

            </form>
        </div>
    </main>

    <footer class="container-fluid w-100 px-3 py-4 bg-dark text-white text-center">
        <p class="m-0 fs-5">&copy; Copyright 2024 - mdsl1. Todos os direitos reservados.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="backend/adicionar_carrinho.js" type="text/javascript"></script>

    <script type="text/javascript">

        let priceItem = document.getElementById("priceItem");
        let qtdeItem = document.getElementById("qtdeItem");
        let btnRmv = document.getElementById("rmvItem");
        let btnAdd = document.getElementById("addItem");

        btnRmv.addEventListener("click", () => {
            let q = (parseInt(qtdeItem.value) < 2) ? 1 : parseInt(qtdeItem.value) - 1;
            qtdeItem.value = q;
            console.log("Removido");
            console.log(qtdeItem.value);
            atualizarPreco();
        });

        btnAdd.addEventListener("click", () => {
            qtdeItem.value = (parseInt(qtdeItem.value) + 1);
            console.log("Adicionado");
            console.log(qtdeItem.value);
            atualizarPreco(qtdeItem.value);
        });

        function atualizarPreco() {
            let p = parseFloat(document.getElementById("preco").value) * parseInt(qtdeItem.value);
            //console.log(p);
            priceItem.textContent = `R$ ${p.toFixed(2).replace(".", ",")}`;
        };

    </script>
</body>
</html>