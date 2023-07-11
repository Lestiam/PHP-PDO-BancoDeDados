<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);

/** @var Student[] $studentList */
$studentList = $repository->studentsWithPhones(); //pego só os alunos com telefones

echo $studentList[1]->phones()[0]->formattedPhone(); //vou formatar o primeiro telefone (primeira posição)
var_dump($studentList);
