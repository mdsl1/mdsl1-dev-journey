router.put("/qtde/:id", (req, res) => {

    const params = [
        parseInt(req.body.qtde),
        parseInt(req.params.id)
    ];

    const sql = "UPDATE estoque SET qtde = ? WHERE id = ?";
    db.query(sql, params, (err, result) => {
        if(err) {
            res.status(400).json({ "erro": "Erro ao atualizar a quantidade do produto." });
            return;
        }

        res.status(200).json({ "msg": "Quantidade do produto alterada com sucesso." });
    });
});