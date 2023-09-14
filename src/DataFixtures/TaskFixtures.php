<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $userRef = [$this->getReference("user"), $this->getReference("admin"), $this->getReference("anonyme")];

        for($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle("task$i");
            $task->setContent("content$i");
            $task->setUser($userRef[array_rand($userRef)]);
            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}