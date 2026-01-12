<?php
    session_start();
    require_once "conexao_db.php";

    $email = htmlspecialchars($_POST["email"]);
    $senha = hash("sha256", htmlspecialchars($_POST["senha"]));

    $sql = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $senha]);
    $usuario = $stmt->fetch();

    if(isset($usuario["nome"])) {
        $_SESSION["id_cli"] = $usuario["id"];
        $is_adm = $usuario["is_adm"];
        $_SESSION["is_adm"] = $is_adm;
        
        if(!$is_adm) {
            header("Location: ../index.php");
        }
        if(isset($_SESSION["erro_login"]) && $_SESSION["erro_login"]) {
            unset($_SESSION["erro_login"]);
        }

    } else {
        $_SESSION["erro_login"] = true;
        header("Location: ../login.php");
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

    <main class="container-fluid w-100 py-5 text-center d-flex flex-column justify-content-center" style="height: 100vh;">

        <div class="container w-50 d-flex flex-column justify-content-center align-items-center p-5 rounded-4" style="background-color: #ffffff9d; backdrop-filter: blur(10px);">

            <h1 class="my-5 fw-bolder">Escolher Login</h1>
            
            <p class="mb-3 fs-4">Identificamos que você possui acessos de administrador, como deseja logar?</p>

            <a href="../index.php" class="mt-5 btn btn-primary fs-3 w-50" type="submit">Entrar como Cliente</a>
            
            <a href="../admin/index.php" class="mt-5 btn btn-primary fs-3 w-50 mb-5" type="submit">Entrar como Administrador</a>

        </div>

    </main>

    <div id="bgImg" class="container-fluid position-fixed top-0" style="width: 100vw; height: 100vh; background: url('https://i.pinimg.com/1200x/15/2c/2c/152c2c00f26647f91086a8899dec98d1.jpg') no-repeat; background-size: cover; z-index: -99; "></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>