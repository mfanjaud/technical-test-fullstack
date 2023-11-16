<?php

namespace App\EventListener;

use App\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;

class TaskListener
{

    #[ORM\PrePersist]
    public function prePersist(Task $task, PrePersistEventArgs $args): void
    {
        $task->setCompleted(false);
    }

    #[ORM\PreUpdate]
    public function preUpdate(Task $task, PreUpdateEventArgs $args): void
    {

        if ($args->getNewValue('completed')) {
            $completed = $args->getNewValue('completed');
            $task->setCompleted($completed);
        }
    }
}
