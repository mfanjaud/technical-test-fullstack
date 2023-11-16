<?php

namespace App\EventListener;

use App\Interface\AuthoredEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;

class AuthoredEntityListener
{
    private $tokenStorage;

    public function __construct(UsageTrackingTokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[ORM\PrePersist]
    public function prePersist(AuthoredEntityInterface $entity, LifecycleEventArgs $args): void
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
}
