<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{    
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle("task$i");
            $task->setContent("content$i");
            $task->setUser(new User(rand(0,2)));
            $manager->persist($task);
        }

        $manager->flush();
    }
}