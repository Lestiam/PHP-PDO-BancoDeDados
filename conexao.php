<?php
//cria a instancia de PDO - 1º parametro = string de coneção. Depois do dois pontos, informo as configurações do banco,s e for MySQl eu informaria mysql:host=endereco_do_servidor;dbname=nome_do_banco. Como é um banco de dados SQLite, só preciso informar o caminho.
$databasePath = __DIR__ . '/banco.sqlite'; //a constante DIR pega meu diretorio atual.
$pdo = new PDO('sqlite:' . $databasePath);

echo 'Conectei';

$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('31', '999999999', 1), ('21', '222222222', 1);");
exit();

$createTableSql = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (--cria a tabela de telefones
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,--consigo identificar de quem é esse telefone, isso é uma chave estrageira
        FOREIGN KEY(student_id) REFERENCES students(id)--aqui eu falo que a chave estrangeira student_id faz referencia ao estudante na tabela students, com isso, eu só consigo criar telefones para algum aluno existente
    );
';

$pdo->exec($createTableSql);
