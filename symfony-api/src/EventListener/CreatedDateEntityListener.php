<?php

namespace App\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use App\Interface\CreatedDateEntityInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CreatedDateEntityListener
{

    private $requestStack;
    public function __construct(
        RequestStack $requestStack,
    ) {
        $this->requestStack = $requestStack;
    }

    #[ORM\PrePersist]
    public function prePersist(CreatedDateEntityInterface $entity, PrePersistEventArgs $args): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$entity instanceof CreatedDateEntityInterface || !$request?->isMethod("POST")) {
            return;
        }

        $entity->setCreatedAt(new \DateTime(""));
    }

    #[ORM\PreUpdate]
    public function preUpdate(CreatedDateEntityInterface $entity, PreUpdateEventArgs $args): void
    {
    }
}
