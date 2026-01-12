<?php
    $host = "127.0.0.1:3309";
    $db_name = "db_projeto_php_2";
    $user = "admphp";
    $senha = "Ti@142536.";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $user, $senha);
        $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Conexão bem sucedida.";
    } catch(PDOException $e) {
        die("Erro de conexão: " . $e ->getMessage());
    }
?>