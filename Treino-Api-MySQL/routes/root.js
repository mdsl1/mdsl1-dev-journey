const express = require("express");
const router = express.Router();

router.get("/", (req, res) => {
    res.send(`
        <html>
            <head>
                <title>Minha API</title>
                <meta charset="UTF-8">
                <style>
                    th, td {
                        padding: 1rem;
                    }
                </style>
            </head>
            <body style="display: flex; flex-flow: column nowrap; justify-content: center; align-items: center; height: 100vh;">
                <h1 style="text-align: center;">Bem-vindo(a) à minha API! Acesse os dados em:</h1>
                <table border="1" style="margin-top: 20px;">
                    <tr>
                        <th>Rota</th>
                        <th>Método</th>
                        <th>Descrição</th>
                    </tr>

                    <tr>
                        <td>/api/produtos</td>
                        <td><strong>GET</strong></td>
                        <td>Retorna uma lista de produtos em formato JSON.</td>
                    </tr>

                    <tr>
                        <td>/api/produtos/<i>num_id</i></td>
                        <td><strong>GET</strong></td>
                        <td>Retorna um produto especificado pelo ID em formato JSON.</td>
                    </tr>

                    <tr>
                        <td>/api/produtos/<i>num_id</i></td>
                        <td><strong>PUT</strong></td>
                        <td>Atualiza um produto especificado pelo ID.</td>
                    </tr>

                    <tr>
                        <td>/api/produtos/<i>num_id</i></td>
                        <td><strong>DELETE</strong></td>
                        <td>Deleta um produto especificado pelo ID.</td>
                    </tr>
                </table>
            </body>
        </html>
    `)
});

module.exports = router;