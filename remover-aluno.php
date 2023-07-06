<?php

require_once 'vendor/autoload.php';
use Alura\Pdo\Domain\Model\Student;

$pdo = \Alura\Pdo\Domain\Infrastructure\Persistence\ConnectionCreator::createConnection();

$preparedStatement = $pdo->prepare('DELETE FROM students WHERE id = ?;'); //apago da tabela students, onde o id for igual ao que eu passar
$preparedStatement->bindValue(1,4,PDO::PARAM_INT); //O value é o id de quem eu quero apagar e o terceiro parametro eu especifico que é um inteiro, já que por padrão, seria uma string
$preparedStatement->execute();