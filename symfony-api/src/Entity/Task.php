<?php

namespace App\Entity;

use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Interface\AuthoredEntityInterface;
use App\EventListener\TaskListener;
use App\EventListener\AuthoredEntityListener;
use App\Interface\CreatedDateEntityInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\EventListener\CreatedDateEntityListener;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\EntityListeners([
    TaskListener::class,
    AuthoredEntityListener::class,
    CreatedDateEntityListener::class
])]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: "/task/{id}",
        ),
        new GetCollection(
            order: ["dueDate" => "ASC", "completed" => "ASC"],
            paginationEnabled: false
        ),
        new Post(
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
        ),
        new Delete(
            normalizationContext: ['groups' => ['get-for-delete']],
            uriTemplate: "/task/{id}",
            security: "is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user or object.getTaskList().getAuthor() == user ",
        )
    ],
    normalizationContext: ['groups' => ['get']],
    denormalizationContext: ['groups' => ['post']],
)]

#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'name', 'dueDate', 'completed'])]
class Task implements AuthoredEntityInterface, CreatedDateEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'post'])]
    private string $name;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['get', 'post'])]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\ManyToOne(targetEntity: TaskList::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get-for-delete', 'post'])]
    private $taskList;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get', 'post'])]
    private $author;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column]
    #[Groups(['get'])]
    private bool $completed;

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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): CreatedDateEntityInterface
    {
        $this->createdAt = $createdAt;

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

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getTaskList(): TaskList
    {
        return $this->taskList;
    }

    public function setTaskList(TaskList $taskList): self
    {
        $this->taskList = $taskList;

        return $this;
    }
}
