<?php

namespace App\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\TaskList;

class TaskListListener
{

    #[ORM\PrePersist]
    public function prePersist(TaskList $taskList, LifecycleEventArgs $args): void
    {
        $taskList->setCreationDate(new \DateTime(""));
    }

    #[ORM\PreUpdate]
    public function preUpdate(TaskList $taskList, PreUpdateEventArgs $args): void
    {
        $taskList->setCreationDate(new \DateTime(""));
    }
}
