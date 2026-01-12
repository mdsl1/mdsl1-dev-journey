const mysql = require("mysql2");

const host = "127.0.0.1";
const port = 3309;
const user = "admphp";
const senha = "Ti@142536.";
const db_name = "db_projeto_php_1";


const connection = mysql.createConnection({
    host: host,
    user: user,
    password: senha,
    database: db_name,
    port: port
});

connection.connect(err => {
    if (err) {
        console.error("Erro ao conectar ao banco de dados MySQL: ", err.stack);
        return;
    }
    console.log("Conexão bem sucedida!");
});

module.exports = connection;