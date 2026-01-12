const express = require("express");
const router = express.Router();
const db = require("../db/connection.js");
const sanitizeHtml = require("sanitize-html");

router.get("/", (req, res) => {
    const sql = "SELECT * FROM produtos ORDER BY id";

    db.query(sql, (err, results) => {
        if (err) {
            res.status(404).json({ "erro": "Erro ao buscar produtos." });
            return;
        }

        res.status(200).json(results);
    });
});

router.get("/:id", (req, res) => {
    const id = parseInt(req.params.id);

    if (isNaN(id)) {
        res.status(400).json({ "erro": "ID do produto inválido." });
        return;
    }

    const sql = "SELECT * FROM produtos WHERE id = ?";
    db.query(sql, id, (err, result) => {
        if(err) {
            res.status(404).json({ "erro": "Erro ao localizar o produto. Por segurança, tente novamente." });
            return;
        }
        if(result.length === 0) {
            res.status(404).json({ "erro": "Produto não encontrado." });
            return;
        }

        res.status(200).json(result);
    });
});

router.post("/", (req, res) => {

    const params = [
        sanitizeHtml(req.body.nome),
        sanitizeHtml(req.body.descricao),
        parseFloat(req.body.preco),
        parseInt(req.body.qtde)
    ];

    // Valida se os valores são números ou não
    if (isNaN(params[2]) || isNaN(params[3])) {
        res.status(400).json({ "erro": "Preço e/ou quantidade inválidos." });
        return;
    }

    const sql = "CALL inserir_produto(?, ?, ?, ?)";
    db.query(sql, params, (err, results) => {
        if(err) {
            res.status(400).json({ "erro": "Erro ao adicionar o produto." });
            return;
        }

        res.status(201).json({ "msg": "Produto adicionado com sucesso." });
    });
});

router.put("/:id", (req, res) => {

    const params = [
        sanitizeHtml(req.body.nome),
        sanitizeHtml(req.body.descricao),
        parseFloat(req.body.preco),
        parseInt(req.params.id)
    ];

    // Valida se os valores são números ou não
    if (isNaN(params[2]) || isNaN(params[3])) {
        res.status(400).json({ "erro": "Preço e/ou id inválidos." });
        return;
    }

    const sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ? WHERE id = ?";
    db.query(sql, params, (err, result) => {
        if(err) {
            res.status(400).json({ "erro": "Erro ao localizar o produto. Por segurança, tente novamente." });
            return;
        }
        if(result.affectedRows === 0) {
            res.status(404).json({ "erro": "Produto não encontrado." });
        }

        res.status(200).json({ "msg": "Informações atualizadas com sucesso." });
    });
});

router.delete("/:id", async (req, res) => {
    
    const id = parseInt(req.params.id);

    if (isNaN(id)) {
        res.status(400).json({ "erro": "ID do produto inválido." });
        return;
    }

    try {
        // Primeiro delete das foreign keys
        let sql = "DELETE FROM estoque WHERE id_produto = ?";
        await db.execute(sql, [id]);

        // Segundo delete da linha pai
        sql = "DELETE FROM produtos WHERE id = ?";
        await db.execute(sql, [id]);

        res.status(200).json({ "msg": "Produto deletado com sucesso." });

    } catch (err) {
        res.status(404).json({ "erro": "Erro ao deletar o produto." });
    }
});

module.exports = router;