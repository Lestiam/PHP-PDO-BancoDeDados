<?php

namespace Alura\Pdo\Domain\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
USE PDO;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection) //passo a conexão por parametro. É UMA INJEÇÃO DE DEPENDENCIA
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $sqlQuery = 'SELECT * FROM students;';
        $stmt = $this->connection->query($sqlQuery); //como eu não tenho nenhum parametro, eu não preciso do prepare

        return $this->hydrateStudentList($stmt);
    }

    public function studentsBirthAt(\DateTimeInterface $birthDate): array
    {
        $sqlQuery = 'SELECT * FROM students WHERE birth_date = ?;'; //traz os alunos nascidos em determinada data
        $stmt = $this->connection->prepare($sqlQuery); //estou preparando o prepareStatement
        $stmt->bindValue(1, $birthDate->format('Y-m-d')); //passo a data de nascimento informada por parametro
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }

    private function hydrateStudentList(\PDOStatement $stmt) : array { //esse padrão que se chama hidratar, eu transfiro dado de uma camada para outra. Trago dados do banco de dados para a nossa classe
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC); //eu só consigo fazer fetch, fetchall depois de executar (execute) o meu statment. Aqui eu busco todos os resultados desse statment no formato de array associativo
        $studentList = []; //inicializo uma lista vazia dos alunos

        foreach ($studentDataList as $studentData) { //faço um foreach dos dados que eu trouxe do banco de dados
            $studentList[] = new Student( //adiciono na lista de alunos um novo aluno criado com os dados do banco
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentList; //devolvo a nossa lista de alunos
    }

    public function save(Student $student): bool
    {
        if ($student->id() === null) { //se o id dele for nulo, então ele não existe
            return $this->insert($student); //aí ele insere
        }

        return $this->update($student); //se o id não for nulo, ele atualiza esse aluno
    }

    private function insert(Student $student): bool
    {
        $insertQuery = 'INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);'; //insere em estudantes o nome e a data de nascimento utilizando dos parametros nomeados (:name, :birth_date)
        $stmt = $this->prepare($insertQuery);

        $sucess = $stmt->execute([ //no execute, utilizao um array associativo onde a chave é o nome do parametro e o valor é o parametro que eu quero passar
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d'),
        ]);

        if ($sucess) {
            $student->defineId($this->connection->lastInsertId());//se for bem sucedido, eu defino o ID desse aluno. O PHP tem este método chamado lastInsertId que ele pega o ID da ultima inserção no banco
        }

        return $sucess; //retorno se foi um sucesso ou não
    }

    private function update(Student $student):bool
    {
        $updateQuery = 'UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;'; //faz um update onde o id for igual ao id passado
        $stmt = $this->connection->prepare($updateQuery);
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $stmt->bindValue(':id', $student->id(), PDO::PARAM_INT);//o id é inteiro

        return $stmt->execute();
    }
    public function remove(Student $student): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM students WHERE id = ?;'); //removo da tabela dos alunos onde o id for o parametro que eu vou passar (?).
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);

        return $stmt->execute(); //devolvo o resultado do execute para saber se a remoção foi feita com sucesso ou não
    }
}