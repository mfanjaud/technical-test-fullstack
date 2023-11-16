<?php

namespace App\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserCreationListener
{

    private $userPasswordHasherInterface;

    public function  __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    #[ORM\PrePersist]
    public function prePersist(User $user, LifecycleEventArgs $args): void
    {
        $user->setCreationDate(new \DateTime(""));

        $password = $user->getPassword();
        $password = $this->encodePassword($user, $password);
        $user->setPassword($password);
    }

    #[ORM\PreUpdate]
    public function preUpdate(User  $user, PreUpdateEventArgs $args): void
    {
        $user->setCreationDate(new \DateTime(""));

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
