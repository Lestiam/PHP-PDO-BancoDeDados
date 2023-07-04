<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite'; //a constante DIR pega meu diretorio atual.
$pdo = new PDO('sqlite:' . $databasePath);

$statement = $pdo->query('SELECT * FROM students;'); //chama a função query para trazer todos os alunos

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC); //retorna um array contendo todas as informações, indices numeros, nomes e até objetos. o fetchAll busca todos os dados, mas passando o PDO::FETCH_ASSOC como parametro, ele retorna apenas um unico valor por nome de coluna
//se eu usar o fetch ao inves do fetchAll, ele me retorna uma linha com 1 resultado e a vantagem, é que eu não preciso usar um foreach para trazer estes resultados.

//while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)) { //enquanto a variavel $studentData qu esta recebendo o dado atual do banco de dados for um valor válido... eu executo este código
//    $student = new Student(
//        $studentData['id'],
//        $studentData['name'],
//        new DateTimeImmutable($studentData['birth_date'])
//    );

//    echo $student->age() . PHP_EOL; //imprimo a idade od estudante
//    //ao inves de pegar todos os estudante de uma vez só, eu to pegando 1 por 1 com o fetch e exibindo sua idade e na próxima interação, eu jogo a instancia do estudante anterior fora e ele não ocupa mais memoria.
//    //e quando acabarem as linhas do banco de dados, o fetch vai me retornar false no $studentData e o while vai sair do loop
//}


$studentList = []; //é uma lista de objetos do tipo estudante

foreach ($studentDataList as $studentData) { //é um foreach para utilizar o FETCH_ASSOC instanciando os objetos corretamente com os valores gerados e fazendo o mapeamento quando for necessario.
    $studentList[] = new Student(
        $studentData['id'],
        $studentData['name'],
        new DateTimeImmutable($studentData['birth_date'])
    );
}

var_dump($studentList);

//echo $studentList[0]['name']; //aqui informo o indice no array e a coluna que quero que me retorne, poderia chamar [0]['id'] por exemplo

