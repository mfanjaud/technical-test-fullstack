<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id ;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\ManyToOne(targetEntity: TaskList::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable:false)]
    private $taskList;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable:false)]
    private $author;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creationDate;

    #[ORM\Column]
    private bool $completed;

    #[ORM\Column]
    private bool $deleted;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getCreationDate(): \DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getAuthor(): User {
        return $this->author;
    }

    public function setAuthor(User $author): self {
         $this->author = $author;

         return $this;
    }

    public function getTaskList(): TaskList {
        return $this->taskList;
    }

    public function setTaskList(TaskList $taskList): self {
         $this->taskList = $taskList;

         return $this;
    }
}
