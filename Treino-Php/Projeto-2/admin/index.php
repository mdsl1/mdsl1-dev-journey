<?php
    session_start();
    require_once "../backend/conexao_db.php";
    
    if(!isset($_SESSION["id_cli"]) || !$_SESSION["is_adm"]) {
        header("Location: nao_admin.php");
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
    <header class="navbar navbar-expand-lg bg-dark text-white px-3 py-2 d-flex justify-content-between">

        <div id="logoContainer" class="container w-50 d-flex align-items-center justify-content-start">
            <div id="logo" class="d-flex align-items-center justify-content-center bg-dark text-white fs-5" style="width: 4rem; height: 4rem; border: 2px solid #3da1ff; border-radius: 50%; margin-right: 2rem;">1</div>
            <h1>Projeto em PHP 1</h1>
        </div>

        <nav class="container w-75 d-flex align-items-baseline justify-content-end">
            <ul class="navbar-nav d-flex flex-row gap-4 fs-4">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">...</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">....</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">.....</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">......</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container w-75 my-3 mx-auto py-5 text-center d-flex flex-column justify-content-center" style="height: 83vh;">

        <h1 class="mb-4">Novidades em Breve</h1>

    </main>

    <footer class="container-fluid w-100 px-3 py-4 bg-dark text-white text-center">
        <p class="m-0 fs-5">&copy; Copyright 2024 - mdsl1. Todos os direitos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>