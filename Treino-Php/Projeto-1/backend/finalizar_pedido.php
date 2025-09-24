<?php
    session_start();
    date_default_timezone_set("America/Sao_Paulo");
    require_once "conexao_db.php";

    if(!isset($_SESSION["id_cli"])) {
        header("Location: ../index.php");
    }

    if(!isset($_SESSION["carrinho"]) || empty($_SESSION["carrinho"])) {
        die("Carrinho vazio.");
    }

    $id = intval(htmlspecialchars($_SESSION["id_cli"]));
    $data = date("Y-m-d H:i:s");
    $strIds = "";
    $strQtdes = "";

    foreach($_SESSION["carrinho"] as $p => $qtde) {
        
        $strIds .= $p;
        $strQtdes .= $qtde;

        if(array_key_last($_SESSION["carrinho"]) !== $p) {
            $strIds .= ",";
            $strQtdes .= ",";
        }
    }

    $sql = "CALL criar_pedido(?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$id, $strIds, $strQtdes]);

    unset($_SESSION["carrinho"]);
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

    <main class="container w-75 my-3 mx-auto py-5 text-center d-flex flex-column justify-content-center" style="height: 83vh;">

        <h1 class="mb-4">Finalizar Pedido</h1>
        
        <?php if($result) { ?>
            
            <p class="fs-4 my-5">Pedido finalizado com sucesso!</p>
            <a href="index.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover fs-5">Clique aqui para voltar à loja</a>   

        <?php } else { ?>
            
            <p class="fs-4 my-5">Ocorreu um erro ao processar o seu pedido. Por segurança, tente novamente mais tarde.</p>
            <a href="index.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover fs-5">Clique aqui para voltar à loja</a>       


        <?php } ?>

    </main>

    <footer class="container-fluid w-100 px-3 py-4 bg-dark text-white text-center">
        <p class="m-0 fs-5">&copy; Copyright 2024 - mdsl1. Todos os direitos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>