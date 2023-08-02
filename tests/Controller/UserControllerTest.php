<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testDisplayUser(): void
    {
        $this->client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testDisplayCreateUser(): void
    {
        $this->client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessCreateUser(): void
    {
        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'username' => 'usertest',
            'password' => 'password',
            'email' => 'unemailtest@email.email'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDisplayEditUser(): void
    {
        $this->client->request('GET', '/user/2/edit');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessEditTask(): void
    {
        $crawler = $this->client->request('GET', '/tasks/2/edit');
        
        $form = $crawler->selectButton('Modifier')->form([
            'username' => 'usertest2',
            'password' => 'password2',
            'email' => 'unemailtest2@email.email'
        ]);
        
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->assertSelectorExists('.alert.alert-success');
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $task = $userRepository->find(2);

        $this->assertNotNull($task->getId());
        $this->assertSame("usertest2", $task->getUsername());
    }
}