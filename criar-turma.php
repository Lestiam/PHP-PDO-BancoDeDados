<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);

$connection->beginTransaction(); //quando eu mando uma query, ele não há executa diretamente no banco, ele a deixa guardada para executar somente quando eu finalizar a transação. Aqui eu inicio a transação

try {
    $aStudent = new Student(
        null,
        'Nico Steppat',
        new DateTimeImmutable('1985-05-01'),
    );
    $studentRepository->save($aStudent);

    $anotherStudent = new Student(
        null,
        'Sergio Lopes',
        new DateTimeImmutable('1985-05-01'),
    );
    $studentRepository->save($anotherStudent);

    $connection->commit(); //encerra a transação e manda todas as inserções para o banco de dados. Se o processo for parado antes de chegar aqui, nenhum aluno é salvo.
} catch (\PDOException $e) {
    echo $e->getMessage();
    $connection->rollBack(); //cancela uma transação
}


