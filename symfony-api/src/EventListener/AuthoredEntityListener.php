<?php

namespace App\EventListener;

use Doctrine\ORM\Mapping as ORM;
use App\Interface\AuthoredEntityInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;

class AuthoredEntityListener
{
    private $tokenStorage;

    public function __construct(UsageTrackingTokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[ORM\PrePersist]
    public function prePersist(AuthoredEntityInterface $entity, PrePersistEventArgs $args): void
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token) {
            return;
        }

        $author = $token->getUser();

        if (!$entity instanceof AuthoredEntityInterface) {
            return;
        }

        $entity->setAuthor($author);
    }

    #[ORM\PreUpdate]
    public function preUpdate(AuthoredEntityInterface $entity, PreUpdateEventArgs $args): void
    {
    }
}
