<?php

namespace App\Entity;

use App\Entity\Task;
use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskListRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Interface\AuthoredEntityInterface;
use App\Interface\CreatedDateEntityInterface;
use Doctrine\Common\Collections\Collection;
use App\EventListener\AuthoredEntityListener;
use App\EventListener\CreatedDateEntityListener;
use App\EventListener\TaskListCreationListener;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskListRepository::class)]
#[ORM\EntityListeners([
    TaskListCreationListener::class,
    AuthoredEntityListener::class,
    CreatedDateEntityListener::class
])]
#[ApiResource(
    operations: [new Get(), new GetCollection()],
    normalizationContext: ['groups' => ['read']],
)]
#[Post(security: "is_granted('IS_AUTHENTICATED_FULLY')")]

class TaskList implements AuthoredEntityInterface, CreatedDateEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    #[Assert\NotBlank()]
    #[Assert\Length(null, 3, 255)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'taskLists')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private $author;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'taskList')]
    #[Groups(['read'])]
    private $tasks;

    #[ORM\Column]
    private bool $deleted;

    public function __construct()
    {
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

    public function setCreationDate(\DateTimeInterface $creationDate): CreatedDateEntityInterface
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

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }
}
