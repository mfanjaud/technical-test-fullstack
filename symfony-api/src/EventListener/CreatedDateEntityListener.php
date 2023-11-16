<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Interface\CreatedDateEntityInterface;
use Doctrine\ORM\Mapping as ORM;

class CreatedDateEntityListener
{

    #[ORM\PrePersist]
    public function prePersist(CreatedDateEntityInterface $entity, LifecycleEventArgs $args): void
    {

        if (!$entity instanceof CreatedDateEntityInterface) {
            return;
        }

        $entity->setCreationDate(new \DateTime(""));
    }
}
