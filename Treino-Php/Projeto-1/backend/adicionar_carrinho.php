<?php   
    session_start();

    if(!isset($_SESSION["id_cli"])) {
        header("Location: ../index.php");
    }

    $id = intval(htmlspecialchars($_POST["id_p"]));
    $qtde = intval(htmlspecialchars($_POST["qtde_p"]));

    echo "$qtde";

    if(!$id || $qtde < 1) {
        http_response_code(400);
        echo "Ocorreu um erro ao adicionar o produto no carrinho.";
        exit;
    }

    if(!isset($_SESSION["carrinho"])) {
        $_SESSION["carrinho"] = [];
    }

    if(!isset($_SESSION["carrinho"][$id])) {
        $_SESSION["carrinho"][$id] = $qtde;
    }
    else {
        $_SESSION["carrinho"][$id] += $qtde;
    }
    http_response_code(201);
    echo "Produto com id $id adicionado ao carrinho com quantidade $qtde";

?>