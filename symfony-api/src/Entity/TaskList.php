<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskListRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\User;
use App\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskListRepository::class)]
#[ApiResource]
class TaskList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'taskLists')]
    #[ORM\JoinColumn(nullable:false)]
    private $author;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'taskList')]
    private $tasks;

    #[ORM\Column]
    private bool $deleted;

       public function __construct(){
        $this->tasks = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getTasks(): ArrayCollection{
        return $this->tasks;
    }
}
