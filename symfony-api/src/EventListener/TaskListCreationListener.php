<?php

namespace App\EventListener;

use App\Entity\TaskList;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;

class TaskListCreationListener
{

    #[ORM\PrePersist]
    public function prePersist(TaskList $taskList, LifecycleEventArgs $args): void
    {
        $this->setDefaultFields($taskList);
    }

    #[ORM\PreUpdate]
    public function preUpdate(TaskList $taskList, PreUpdateEventArgs $args): void
    {
        $this->setDefaultFields($taskList);
    }

    private function setDefaultFields(TaskList $taskList)
    {
        $taskList->setDeleted(false);
    }
}
