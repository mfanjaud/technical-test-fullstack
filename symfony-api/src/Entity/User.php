<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TaskList;
use App\Entity\Task;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\EventListener\UserListener;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(["email"])]
#[UniqueEntity("username")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners([UserListener::class])]
#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post()],
    normalizationContext: ['groups' => ['read']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    #[Assert\NotBlank()]
    #[Assert\Length(null, 6, 255)]
    private string $username;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        "/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        "Password must be at least 7 characters long and contain at least : one digit, one upper case letter and one lower case letter."
    )]
    private string $password;

    #[Assert\NotBlank()]
    #[Assert\Expression(
        "this.getPassword() === this.getConfirmPassword()",
        "Passwords do not match."
    )]
    private string $confirmPassword;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creationDate;

    #[ORM\OneToMany(targetEntity: TaskList::class, mappedBy: 'author')]
    #[Groups(['read'])]
    private $taskLists;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'author')]
    #[Groups(['read'])]
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

    public function getConfirmPassword(): string
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

    public function getCreationDate(): \DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;
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

    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
