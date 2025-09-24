function comprarAgora() {

    let id_p = document.getElementById("id").value;
    let qtde_p = parseInt(document.getElementById("qtdeItem").value);

    fetch("backend/adicionar_carrinho.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:`id_p=${id_p}&qtde_p=${qtde_p}`
    })
    .then(res => res.text())
    .then(data=> {
        console.log(data);
        window.location.href = "carrinho.php";
    });
};

function addToCarrinho() {

    let id_p = document.getElementById("id").value;
    let qtde_p = document.getElementById("qtdeItem").value;

    fetch("backend/adicionar_carrinho.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:`id_p=${id_p}&qtde_p=${qtde_p}`
    })
    .then(res=> res.text())
    .then(data=> {
        console.log(data);
        alert("Produto adicionado ao carrinho.");
        window.location.href = "index.php";
    })
};
