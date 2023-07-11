<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO //cria um método estatico que tem como unica responsabilidade, me devolver um objeto que eu conheço e depois eu extraio a criação dele para um local especifico
    {
        $databasePath = __DIR__ . '/../../../banco.sqlite'; //a constante DIR pega meu diretorio atual.
        $connection =  new PDO('sqlite:' . $databasePath);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //atribuo o atributo de modo de erro para o ERRMODE_exception que lança a excessão. Ele vem da classe do PDO
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $connection;
    }

}