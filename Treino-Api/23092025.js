const express = require ("express");
const app = express();
app.use(express.json());
const port = 3000;

app.listen(port => {
    console.log("Servidor rodando em 127.0.0.1:" + port);
});

app.get("/api/produtos", (req, res) => {
    res.status(200).json({"sucesso": "O valor sera enviado pelo banco de dados."})
});