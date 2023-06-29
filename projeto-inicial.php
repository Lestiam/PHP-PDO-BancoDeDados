<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$student = new Student(
    null,
    'Igor Teles',
    new \DateTimeImmutable('1993-03-06')
);

echo $student->age();
