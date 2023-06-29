<?php
//cria a instancia de PDO - 1º parametro = string de coneção. Depois do dois pontos, informo as configurações do banco,s e for MySQl eu informaria mysql:host=endereco_do_servidor;dbname=nome_do_banco. Como é um banco de dados SQLite, só preciso informar o caminho.
$caminhoBanco = __DIR__ . '/banco.sqlite'; //a constante DIR pega meu diretorio atual.
$pdo = new PDO('sqlite:' . $caminhoBanco);

echo 'Conectei';
