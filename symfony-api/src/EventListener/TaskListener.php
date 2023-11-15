<?php

namespace App\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Task;

class TaskListener
{

    #[ORM\PrePersist]
    public function prePersist(Task $task, LifecycleEventArgs $args): void
    {
        $task->setCreationDate(new \DateTime(""));
    }

    #[ORM\PreUpdate]
    public function preUpdate(Task $task, PreUpdateEventArgs $args): void
    {
        $task->setCreationDate(new \DateTime(""));
    }
}
