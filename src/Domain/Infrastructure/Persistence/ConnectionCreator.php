<?php

namespace Alura\Pdo\Domain\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO //cria um método estatico que tem como unica responsabilidade, me devolver um objeto que eu conheço e depois eu extraio a criação dele para um local especifico
    {
        $databasePath = __DIR__ . '/../../../banco.sqlite'; //a constante DIR pega meu diretorio atual.
        return new PDO('sqlite:' . $databasePath);
    }

}