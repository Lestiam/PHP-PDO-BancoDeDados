<?php

namespace Alura\Pdo\Domain\Model;

class Student
{
    private ?int $id;
    private string $name;
    private \DateTimeInterface $birthDate;
    /** @var Phone[] */ //a IDE vai me informar que esse cara é do tipo phone e vai me ajduar com o auto-complete
    private array $phones = []; //inicializo como um array vazio porque todo aluno que eu criar, vai começar sem telefone nenhum

    public function __construct(?int $id, string $name, \DateTimeInterface $birthDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthDate = $birthDate;
    }

    public function defineId(int $id): void //possibilita que o ID seja definido, depois de criarmos o objeto, caso a gente crie um aluno sem id, a gente pode definir o id dele
    {
        if (!is_null($this->id)) {
            throw new \DomainException('Você só pode definir o ID uma vez');
        }
        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function changeName(string $newName): void //possibilita que o aluno mude de nome
    {
        $this->name = $newName;
    }

    public function birthDate(): \DateTimeInterface
    {
        return $this->birthDate;
    }

    public function age(): int
    {
        return $this->birthDate
            ->diff(new \DateTimeImmutable())
            ->y;
    }

    public function addPhone(Phone $phone): void
    {
        $this->phones[] = $phone; //adiciono no array de telefones, esse telefone que eu recebi
    }

    /** @return Phone[] */
    public function phones(): array //recupera o telefone
    {
        return $this->phones;
    }
}
