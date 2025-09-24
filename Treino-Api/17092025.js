const express = require ('express');
const app = express();
app.use(express.json());
const port = 3000;

app.listen(port, () => {
    console.log("Servidor rodando em 127.0.0.1:" + port);
});

app.get("/api", (req, res) => {
    res.send(`<h1>Api do dia 17/09/2025</h1>`);
});

let clientes = [
    {
        id: 1,
        nome: "Cliente 1",
        email: "aaa@gmail.com",
        telefone: "11999999999"
    },
    {
        id: 2,
        nome: "Cliente 2",
        email: "bbb@gmail.com",
        telefone: "11888888888"
    },
    {
        id: 3,
        nome: "Cliente 3",
        email: "ccc@gmail.com",
        telefone: "11777777777"
    }
];

app.get("/api/clientes", (req, res) => {
    res.status(200).json(clientes);
});

app.get("/api/clientes/:id", (req, res) => {
    const cliente = clientes.find(cliente => cliente.id === parseInt(req.params.id));

    if(!cliente) {
        res.status(404).json({ "erro": "Cliente não encontrado." });
    }
    else {
        res.status(200).json(cliente);
    }
});

app.post("/api/clientes/", (req, res) => {
    const dados = req.body;

    let newCliente = {
        id: clientes.length + 1,
        nome: dados.nome,
        email: dados.email,
        telefone: dados.telefone
    };

    clientes.push(newCliente);
    res.status(201).json({ "mensagem": "Cliente criado com sucesso." });
});

app.put("/api/clientes/:id", (req, res) => {
    const indice = clientes.findIndex((cliente) => cliente.id === parseInt(req.params.id));
    const dados = req.body;

    if(indice < 0) {
        res.status(404).json({ "erro": "Cliente não encontrado."});
    }
    else {
        let clienteAlterado = {
            id: dados.id,
            nome: dados.nome,
            email: dados.email,
            telefone: dados.telefone
        };

        clientes[indice] = clienteAlterado;
        res.status(200).json({ "mensagem": "Cliente alterado com sucesso."});
    }
});

app.delete("/api/clientes/:id", (req, res) => {
    const indice = clientes.findIndex((cliente) => cliente.id === parseInt(req.params.id));

    if(indice < 0) {
        res.status(404).json({ "erro": "Cliente não encontrado" });
    }
    else {
        clientes.splice(indice, 1);
        res.status(200).json({ "mensagem": "Cliente excluido com sucesso." });
    }
});