const express = require('express');
const app = express();
app.use(express.json());
const port = 3000;

app.listen(port => {
    console.log("Servidor rodando em 127.0.0.1:" + port);
});

app.get("/api/", (req, res) => {
    res.send("<h1>API do dia 19/09/2025.</h1>")
});

let animais = [
    {
        id: 1,
        especie: "Cachorro",
        nome: "Caramelo",
        genero: "Macho"
    },
    {
        id: 2,
        especie: "Gato",
        nome: "Xaninha",
        genero: "Femea"
    },
    {
        id: 3,
        especie: "Pinguim",
        nome: "Rodolfo",
        genero: "GigaChad"
    }
];

app.get("/api/animais", (req, res) => {
    res.status(200).json( animais );
});

app.get("/api/animais/:id", (req, res) => {
    const animal = animais.find(animal => animal.id === parseInt(req.params.id));

    if(!animal) {
        res.status(404).json({ "msg": "Animal não encontrado." });
    }
    else {
        res.status(200).json( animal );
    }
});

app.post("/api/animais", (req, res) => {
    const dados = req.body;

    let newAnimal = {
        id: animais.length + 1,
        especie: dados.especie,
        nome: dados.nome,
        genero: dados.genero
    };

    animais.push(newAnimal);
    res.status(201).json({ "msg": "Animal criado com sucesso."});
});

app.put("/api/animais/:id", (req, res) => {
    const indice = animais.findIndex((animal) => animal.id === parseInt(req.params.id));
    const dados = req.body;

    if(indice < 0) {
        res.status(404).json({ "msg": "Animal não encontrado."});
    }
    else {
        let animalAlterado = {
            id: dados.id,
            especie: dados.especie,
            nome: dados.nome,
            genero: dados.genero
        };

        animais[indice] = animalAlterado;
        res.status(200).json({ "msg": "Animal alterado com sucesso."});
    }
});

app.delete("/api/animais/:id", (req, res) => {
    const indice = animais.findIndex((animal) => animal.id === parseInt(req.params.id));

    if(indice < 0) {
        res.status(404).json({ "msg": "Animal não encontrado." });
    }
    else {
        animais.splice(indice, 1);
        res.status(200).json({ "msg": "Animal excluido com sucesso." });
    }
});

