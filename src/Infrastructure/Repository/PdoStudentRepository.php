<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use http\Exception\RuntimeException;
use PDO;

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

    private function hydrateStudentList(\PDOStatement $stmt): array
    { //esse padrão que se chama hidratar, eu transfiro dado de uma camada para outra. Trago dados do banco de dados para a nossa classe
        $studentDataList = $stmt->fetchAll(); //eu só consigo fazer fetch, fetchall depois de executar (execute) o meu statment. Aqui eu busco todos os resultados desse statment no formato de array associativo
        $studentList = []; //inicializo uma lista vazia dos alunos

        foreach ($studentDataList as $studentData) { //faço um foreach dos dados que eu trouxe do banco de dados
            $studentList[] = new Student( //nessa student list eu adiciono a variavel student e dentro da variavel eu recebo um novo student
                $studentData['id'],
                $studentData['name'],
                new \DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentList; //devolvo a nossa lista de alunos
    }

//    private function fillPhonesOf(Student $student): void //no aluno que me foi passado por parametro eu estou adicionando os telefones
//    {
//        $sqlQuery = 'SELECT id, area_code, number FROM phones WHERE student_id = ?'; //da um select nessas colunas onde o id do aluno seja igual ao que eu vou apssar por parametro
//        $stmt = $this->connection->prepare($sqlQuery); //preparo meu statement
//        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT); //preencho esse parametro com o student id e informo que ele é um inteiro
//        $stmt->execute();
//
//        $phoneDataList = $stmt->fetchAll(); //busco todos
//        foreach ($phoneDataList as $phoneData) {
//            $phone = new Phone( //crio meu telefone
//                $phoneData['id'],
//                $phoneData['area_code'],
//                $phoneData['number']
//            );
//
//            $student->addPhone($phone); //adiciono meu telefone aquele estudante
//        }
//    }

    public function save(Student $student): bool
    {
        if ($student->id() === null) { //se o id dele for nulo, então ele não existe
            return $this->insert($student); //aí ele insere
        }

        return $this->update($student); //se o id não for nulo, ele atualiza esse aluno
    }

    private function insert(Student $student): bool
    {
        $insertQuery = 'INSERT INTO students(name, birth_date) VALUES (:name, :birth_date);'; //insere em estudantes o nome e a data de nascimento utilizando dos parametros nomeados (:name, :birth_date)
        $stmt = $this->connection->prepare($insertQuery);


        $success = $stmt->execute([ //no execute, utilizao um array associativo onde a chave é o nome do parametro e o valor é o parametro que eu quero passar
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d'),
        ]);

        if ($success) {
            $student->defineId($this->connection->lastInsertId());//se for bem sucedido, eu defino o ID desse aluno. O PHP tem este método chamado lastInsertId que ele pega o ID da ultima inserção no banco
        }

        return $success; //retorno se foi um sucesso ou não
    }

    private function update(Student $student): bool
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

    public function studentsWithPhones(): array //trago todos estes dados da tabela estudantes fazendo uma junção com a tabela telefones e isso vai funcionar onde o id do aluno for igual ao id do celular do aluno
    {
        $sqlQuery = 'SELECT students.id,
                           students.name,
                           students.birth_date,
                           phones.id AS phone_id,
                           phones.area_code,
                           phones.number
                      FROM students
                      JOIN phones ON students.id = phones.student_id;';
        $stmt = $this->connection->query($sqlQuery);
        $result = $stmt->fetchAll(); //pego o resultado onde tem o aluno duplicado e seus telefones
        $studentList = [];

        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $studentList)) { //se no meu array NÃO existir esse aluno ainda, eu vou criar outro
                $studentList[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new \DateTimeImmutable($row['birth_date'])
                );

            }
            $phone = new Phone($row['phone_id'], $row['area_code'], $row['number']); //se já existir, eu só vou criar um novo telefone e adicionar ao aluno que esta cadastrado naquele ID
            $studentList[$row['id']]->addPhone($phone); //nesse studentList do row id, eu só vou adicionar esse novo telefone
        }

        return $studentList; //retorno a studentList montada
    }
}