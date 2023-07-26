<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntityInit(): void
    {
        $user = new User();

        $this->assertIsObject($user);

        $user->setEmail("unemail@email.email");
        $user->setUsername("username");
        $user->setPassword("password");

        $this->assertNull($user->getId());
        $this->assertSame("unemail@email.email", $user->getEmail());
        $this->assertSame("username", $user->getUsername());
        $this->assertSame("username", $user->getUserIdentifier());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
        $this->assertSame('password', $user->getPassword());
    }

    public function testUserEntityRoles(): void
    {
        $user = new User();

        $this->assertIsObject($user);

        $this->assertSame(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertSame(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testUserEntityEraseCredentials(): void
    {
        $user = new User();

        $this->assertIsObject($user);

        $user->setPassword("password");

        /** check if the set is work */
        $this->assertSame("password", $user->getPassword());

        /** erase password value */
        $user->eraseCredentials();

        /** check if the erase work */
        $this->assertNull($user->getPassword());
    }
}
