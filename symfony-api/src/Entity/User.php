<?php

namespace App\Entity;

use App\Entity\Task;
use App\Entity\TaskList;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\EventListener\UserListener;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use App\Interface\CreatedDateEntityInterface;
use App\EventListener\CreatedDateEntityListener;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity(["email"])]
#[UniqueEntity("username")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners([
    UserListener::class,
    CreatedDateEntityListener::class
])]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: "/user/{id}",
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
        ),
        new GetCollection(),
        new Post(),
        new Delete(uriTemplate: "/user/{id}", security: "is_granted('IS_AUTHENTICATED_FULLY')",),
        // new Put(
        //     uriTemplate: "/user/{id}",
        //     security: "is_granted('IS_AUTHENTICATED_FULLY') and object == user",
        //     denormalizationContext: ['groups' => ['put']],
        //     deserialize: false,
        // )
    ],
    normalizationContext: ['groups' => ['get']],
    denormalizationContext: ['groups' => ['post']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, CreatedDateEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'post'])]
    #[Assert\NotBlank()]
    #[Assert\Length(null, 6, 255)]
    private string $username;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Groups(['post'])]
    #[Assert\Regex(
        "/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        "Password must be at least 7 characters long and contain at least : one digit, one upper case letter and one lower case letter."
    )]
    private string $password;

    #[Assert\NotBlank()]
    #[Groups(['post'])]
    #[Assert\Expression(
        "this.getPassword() === this.getConfirmPassword()",
        "Passwords do not match.",
        groups: ["post"]
    )]
    private string $confirmPassword;

    #[ORM\Column(length: 255)]
    #[Groups(['post'])]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\OneToMany(targetEntity: TaskList::class, mappedBy: 'author')]
    private $taskLists;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'author')]
    private $tasks;

    public function __construct()
    {
        $this->taskLists = new ArrayCollection();
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): static
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
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

    public function getTaskLists(): Collection
    {
        return $this->taskLists;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
