<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TaskList;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[UniqueEntity("email")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $username;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column]
    private array $roles;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creationDate;

    #[ORM\OneToMany(targetEntity: TaskList::class, mappedBy: 'author')]
    private $taskLists;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'author')]
    private $tasks;

    public function __construct(){
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles() :array
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

    public function getTaskLists(): ArrayCollection{
        return $this->taskLists;
    }

    public function getTasks(): ArrayCollection{
        return $this->tasks;
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }

     public function getUserIdentifier():string
    {
        return $this->username;
    }
}
