<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\TaskList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasherInterface;
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadTaskLists($manager);
        $this->loadTasks($manager);
    }

    public function loadTaskLists(ObjectManager $manager): void
    {
        $name = "Task List";
        $description = "Task List for User";

        for ($i = 0; $i < 10; $i++) {
            $user = $this->getReference("user_admin" . $i);

            $taskList = new TaskList;
            $taskList->setName($name . $i);
            $taskList->setDescription($description . $i);
            $taskList->setCreatedAt(new \DateTime(""));
            $taskList->setAuthor($user);
            $this->setReference("task_list" . $i, $taskList);

            $manager->persist($taskList);
        }
        $manager->flush();
        $manager->clear();
    }

    public function loadTasks(ObjectManager $manager): void
    {
        $dueDate = new \DateTime("");
        $dueDate->modify("+1 month");

        for ($i = 0; $i < 10; $i++) {
            $name = "# task to do for list " . $i;
            $user = $this->getReference("user_admin" . $i);
            $taskList = $this->getReference("task_list" . $i);

            for ($ib = 0; $ib < 3; $ib++) {

                $isCompleted = $ib % 2 == 0;

                $task = new Task;
                $taskNumber = $ib + 1;
                $task->setName($taskNumber . $name);
                $task->setDueDate($dueDate);
                $task->setCreatedAt(new \DateTime(""));
                $task->setAuthor($user);
                $task->setTaskList($taskList);
                $task->setCompleted($isCompleted);

                $manager->persist($task);
            }
        }
        $manager->flush();
        $manager->clear();
    }

    public function loadUsers(ObjectManager $manager): void
    {
        $username = "User";
        $email = "@test.fr";

        for ($i = 0; $i < 10; $i++) {
            $password = $username . $i . "secret#";


            $user = new User;
            $user->setUsername($username . $i);
            $user->setEmail($username . $i . $email);
            $user->setRoles([]);
            $user->setCreatedAt(new \DateTime(""));

            $hashedPassword = $this->userPasswordHasherInterface->hashPassword(
                $user,
                $password
            );
            $user->setPassword($hashedPassword);

            $this->setReference("user_admin" . $i, $user);

            $manager->persist($user);
        }
        $manager->flush();
        $manager->clear();
    }
}
