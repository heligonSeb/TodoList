<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory)
    {

    }

    public function load(ObjectManager $manager): void
    {
        # UTILISATEUR
        $user = new User();
        $user->setEmail("user@user.user");
        $user->setRoles([]);
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('password'));
        $user->setUsername('user');
        $manager->persist($user);

        # ADMIN
        $user = new User();
        $user->setEmail("admin@admin.admin");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('password'));
        $user->setUsername('admin');
        $manager->persist($user);

        # ANONYME
        $user = new User();
        $user->setEmail("anonyme@anonyme.anonyme");
        $user->setRoles([]);
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('password'));
        $user->setUsername('anonyme');
        $manager->persist($user);

/*
        for($i = 0; $i < 10; $i++) {
            $user = new User();

            if ($i == 1) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $user->setUsername("user$i");
            $user->setEmail("user$i@domain.fr");
            $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('password'));
            $manager->persist($user);
        }*/

        $manager->flush();
    }
}