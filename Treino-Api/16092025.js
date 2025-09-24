const express = require('express');
const app = express();
app.use(express.json());

const port = 3000;

app.listen(port, () => {
    console.log("Servidor rodando em 127.0.0.1:" + port);
});

// Raiz
app.get("/api", (req, res) => {
    res.send(`<h1>API do dia 16/09/2025</h1>`);
});

// CRUD de produtos

// Array de itens
let produtos = [
    {
        id: 1,
        nome: "Produto 1",
        descricao: "Descrição do produto 1",
        preco: 159.99
    },
    {
        id: 2,
        nome: "Produto 2",
        descricao: "Descrição do produto 2",
        preco: 148.88
    },
    {
        id: 3,
        nome: "Produto 3",
        descricao: "Descrição do produto 3",
        preco: 137.77
    }
];

// Visualizar
app.get("/api/produtos", (req, res) => {
    res.status(200).json(produtos);
});

// Visualizar por ID
app.get("/api/produtos/:id", (req, res) => {
    const produto = produtos.find(produto => produto.id === parseInt(req.params.id));

    if(!produto) {
        res.status(404).json({ "erro": "Produto não encontrado" });
    }
    else {
        res.status(200).json(produto);
    }
});

// Create
app.post("/api/produtos", (req, res) => {
    const dados = req.body;

    let newProduto = {
        id: produtos.length + 1,
        nome: dados.nome,
        descricao: dados.descricao,
        preco: dados.preco
    };

    produtos.push(newProduto);
    res.status(201).json({ "mensagem": "Produto criado com sucesso." });
});

// Update
app.put("/api/produtos/:id", (req, res) => {
    const indice = produtos.findIndex((produto) => produto.id === parseInt(req.params.id));
    const dados = req.body;

    if(indice < 0) {
        res.status(404).json({ "erro": "Produto não encontrado." });
    }
    else {
        let produtoAlterado = {
            id: dados.id,
            nome: dados.nome,
            descricao: dados.descricao,
            preco: dados.preco
        };

        produtos[indice] = produtoAlterado;
        res.status(200).json({ "mensagem": "Produto alterado com sucesso." });
    }
});

// Delete
app.delete("/api/produtos/:id", (req, res) => {
    const indice = produtos.findIndex((produto) => produto.id === parseInt(req.params.id));

    if(indice < 0) {
        res.status(404).json({ "erro": "Produto não encontrado." });
    }
    else {
        produtos.splice(indice, 1);
        res.status(200).json({ "mensagem": "Produto excluido com sucesso." });
    }
});
