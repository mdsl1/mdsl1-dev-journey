<?php
    $senha = "123456";
    $senha_hash = hash('sha256', $senha);

    echo $senha_hash;
?>