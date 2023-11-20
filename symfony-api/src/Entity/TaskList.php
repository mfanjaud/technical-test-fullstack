<?php

namespace App\Entity;

use App\Entity\Task;
use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskListRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Interface\AuthoredEntityInterface;
use Doctrine\Common\Collections\Collection;
use App\EventListener\AuthoredEntityListener;
use App\Interface\CreatedDateEntityInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use App\EventListener\CreatedDateEntityListener;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskListRepository::class)]
#[ORM\EntityListeners([
    AuthoredEntityListener::class,
    CreatedDateEntityListener::class
])]
#[ApiResource(
    paginationClientItemsPerPage: true,
    operations: [
        new Get(
            uriTemplate: "/task_list/{id}",
        ),
        new GetCollection(order: ['createdAt' => 'DESC']),
        new Post(
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
        ),
        new Delete(
            uriTemplate: "/task_list/{id}",
            security: "is_granted('IS_AUTHENTICATED_FULLY') and object.getAuthor() == user",
        )
    ],
    normalizationContext: ['groups' => ['get']],
    denormalizationContext: ['groups' => ['post']],
)]
class TaskList implements AuthoredEntityInterface, CreatedDateEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'post'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[ApiFilter(OrderFilter::class)]
    #[Assert\NotBlank()]
    #[Assert\Length(null, 3, 255)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'post'])]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    #[ApiFilter(OrderFilter::class)]
    #[Groups(['get'])]
    private \DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'taskLists')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get'])]
    private User $author;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'taskList')]
    #[OrderBy(["dueDate" => "ASC", "completed" => "ASC"])]
    #[Groups(['get'])]
    private $tasks;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): CreatedDateEntityInterface
    {
        $this->createdAt = $createdAt;

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
