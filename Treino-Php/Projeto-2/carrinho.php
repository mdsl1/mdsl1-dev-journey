<?php   
    session_start();
    require_once "backend/conexao_db.php";

    if(!isset($_SESSION["id_cli"])) {
        header("Location: index.php");
    }

    $total_carrinho = 0;

    if(!isset($_SESSION["carrinho"]) || empty($_SESSION["carrinho"])) {
        $is_vazio = true;
    }
    else {
        $is_vazio = false;
        
        $ids = array_keys($_SESSION["carrinho"]);
        $placeholder = implode(",", array_fill(0, count($ids), "?"));

        $sql = "SELECT * FROM produtos WHERE id IN ($placeholder)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($ids);

        $produtos = $stmt->fetchAll();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $id = intval(htmlspecialchars($_POST["id"]));

        if(isset($_POST["atualizar"])) {
            $qtde = max(1, intval(htmlspecialchars($_POST["qtde"])));
            $_SESSION["carrinho"][$id] = $qtde;
        }

        if(isset($_POST["remover"])) {
            unset($_SESSION["carrinho"][$id]);
            header("Location: carrinho.php");
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

    <main class="container w-75 my-3 mx-auto py-5 text-center d-flex flex-column justify-content-center" style="height: 83vh;">

        <h1 class="mb-4">Carrinho de Compras</h1>
        
        <?php if($is_vazio) { ?>

            <p class="fs-4 my-5">Seu carrinho está vazio...</p>
            <a href="index.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover fs-5">Clique aqui para voltar à loja</a>

        <?php } else { ?>

            <table class="w-100 table table-striped table-hover">
                <tr class="text-center fs-4 fw-bolder">
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>

                <?php 
                    foreach($produtos as $p):
                        $p_qtde = $_SESSION["carrinho"][$p["id"]];
                        $subtotal = $p["preco"] * $p_qtde;
                        $total_carrinho += $subtotal;
                ?>
                    <tr class="text-center fs-5">
                        <td><?= $p["nome"]; ?></td>
                        <td>R$ <?= number_format($p["preco"], 2, ",", "."); ?></td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center gap-2">
                                <input type="hidden" name="id" value="<?= $p["id"]; ?>">
                                <input type="number" name="qtde" min="1" value="<?= $p_qtde; ?>" class="border border-primary border-opacity-25 w-25 text-center">
                                <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
                            </form>
                        </td>
                        <td>R$ <?= number_format($subtotal, 2, ",", "."); ?></td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center">
                                <input type="hidden" name="id" value="<?= $p["id"]; ?>">
                                <button type="submit" name="remover" class="btn btn-primary">Remover</button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

                    <tr>
                        <td colspan="3" class="fs-4 fw-bolder">Total da Compra:</td>
                        <td colspan="2" class="fs-4 fw-bolder">R$ <?= number_format($total_carrinho, 2, ",", "."); ?></td>
                    </tr>

            </table>

            <form action="backend/finalizar_pedido.php" method="POST">
                <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
            </form>

        <?php } ?>

    </main>

    <footer class="container-fluid w-100 px-3 py-4 bg-dark text-white text-center">
        <p class="m-0 fs-6 fs-md-5">&copy; Copyright 2024 - mdsl1. Todos os direitos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

