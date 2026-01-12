const express = require("express");
const cors = require("cors");
const app = express();
const port = 3000;

app.use(express.json());
app.use(cors());

const rootRouter = require("./routes/root.js");
app.use("/", rootRouter);

const produtosRouter = require("./routes/produtos.js");
app.use("/api/produtos", produtosRouter);


app.listen(port, () => {
    console.log("Servidor rodando em 127.0.0.1:" + port);
});