<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreationListener
{

    private $userPasswordHasherInterface;

    public function  __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    #[ORM\PrePersist]
    public function prePersist(User $user, PrePersistEventArgs $args): void
    {
        $user->setCreatedAt(new \DateTime(""));

        $password = $user->getPassword();
        $password = $this->encodePassword($user, $password);
        $user->setPassword($password);
    }

    #[ORM\PreUpdate]
    public function preUpdate(User  $user, PreUpdateEventArgs $args): void
    {

        if ($args->hasChangedField('password')) {
            $password = $args->getNewValue('password');
            $password = $this->encodePassword($user, $password);
            $user->setPassword($password);
        }
    }

    private function encodePassword(User $user, string $password): string
    {
        return $this->userPasswordHasherInterface->hashPassword($user, $password);
    }
}
