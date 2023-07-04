<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite'; //a constante DIR pega meu diretorio atual.
$pdo = new PDO('sqlite:' . $databasePath);

$student = new Student(
    null,
    "Patricia Freitas",
    new DateTimeImmutable('1986-10-25')); //crio um opjeto aluno no mundo da aplicação

$sqlInsert = "INSERT INTO students(name, birth_date) VALUES (:name, :birth_date);"; //crio um opjeto aluno no mundo do banco de dados. O "?" significa que vou passar um parametro ali,seja ele qual for
$statement = $pdo->prepare($sqlInsert);//mando o meu banco pdo PREPARAR para receber o insert, oui seja, ele vai colocar uma contra barra na frente do nome que o cara digitar,
$statement->bindValue(':name', $student->name()); //lanço como meu primeiro parametro o nome e o PHP já trata isso para mim, só recebe string
$statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d')); //lanço como meu segundo parametro a data de nascimento formatda;

if ($statement->execute()) {// depois que eu preparei tudo acima, eu executo esta instrução, o prepare e o bind ajudam a proteger o código contra injeções SQL
    echo "Aluno incluído";
}

//var_dump($pdo->exec($sqlInsert)); //executa uma instrução SQL e retorna o número de linhas afetadas.