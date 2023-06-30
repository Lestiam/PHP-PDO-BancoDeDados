<?php
//cria a instancia de PDO - 1º parametro = string de coneção. Depois do dois pontos, informo as configurações do banco,s e for MySQl eu informaria mysql:host=endereco_do_servidor;dbname=nome_do_banco. Como é um banco de dados SQLite, só preciso informar o caminho.
$databasePath = __DIR__ . '/banco.sqlite'; //a constante DIR pega meu diretorio atual.
$pdo = new PDO('sqlite:' . $databasePath);

echo 'Conectei';

$pdo->exec('CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXT);');
