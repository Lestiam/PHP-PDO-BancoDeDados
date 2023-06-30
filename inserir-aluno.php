<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite'; //a constante DIR pega meu diretorio atual.
$pdo = new PDO('sqlite:' . $databasePath);

$student = new Student(null, 'Igor Teles', new DateTimeImmutable('1993-03-06')); //crio um opjeto aluno no mundo da aplicação

$sqlInsert = "INSERT INTO students(name, birth_date) VALUES ('{$student->name()}', '{$student->birthDate()->format('Y-m-d')}');"; //crio um opjeto aluno no mundo do banco de dados

var_dump($pdo->egit commitxec($sqlInsert)); //executa uma instrução SQL e retorna o número de linhas afetadas.