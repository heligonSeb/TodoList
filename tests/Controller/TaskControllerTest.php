<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testDisplayTask(): void
    {
        $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testDisplayCreateTask(): void
    {
        $this->client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessCreateTask(): void
    {
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'task10',
            'task[content]' => 'content10'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDisplayEditTask(): void
    {
        $this->client->request('GET', '/tasks/2/edit');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessEditTask(): void
    {
        $crawler = $this->client->request('GET', '/tasks/2/edit');
        
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'task11',
            'task[content]' => 'content11'
        ]);
        
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
        
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertNotNull($task->getId());
        $this->assertSame("task11", $task->getTitle());
        $this->assertSame("content11", $task->getContent());
    }

    public function testDisplayDeleteTask(): void
    {
        $this->client->request('GET', '/tasks/2/delete');

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testToggleTask(): void
    {
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(4);

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());

        $this->client->request('GET', '/tasks/4/toggle');

        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(4);

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertTrue($task->isDone());

        $this->client->request('GET', '/tasks/4/toggle');

        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(4);

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());
    }

    public function testSuccessDeleteTask(): void
    {
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(3);

        $this->assertIsObject($task);
        $this->assertNotNull($task->getId());
        
        $this->client->request('GET', '/tasks/3/delete');
        
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
        
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(3);

        $this->assertNull($task);
    }
}

