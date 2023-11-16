<?php

namespace App\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;

class TaskCreationListener
{

    #[ORM\PrePersist]
    public function prePersist(Task $task, LifecycleEventArgs $args): void
    {
        $this->setDefaultFields($task);
    }

    #[ORM\PreUpdate]
    public function preUpdate(Task $task, PreUpdateEventArgs $args): void
    {
        $this->setDefaultFields($task);
    }

    private function setDefaultFields(Task $task)
    {
        $task->setDeleted(false);
    }
}
