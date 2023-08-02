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
            'title' => 'une task',
            'content' => 'test'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
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
            'title' => 'une task edited',
            'content' => 'test edit'
        ]);
        
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->assertSelectorExists('.alert.alert-success');
        
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertNotNull($task->getId());
        $this->assertSame("une task edited", $task->getTitle());
        $this->assertSame("test edit", $task->getContent());
    }

    public function testDisplayDeleteTask(): void
    {
        $this->client->request('GET', '/tasks/2/delete');

        $this->assertResponseRedirects('/tasks');
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testToggleTaskAction(): void
    {
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());

        $this->client->request('GET', '/tasks/2/toggle');

        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertTrue($task->isDone());

        $this->client->request('GET', '/tasks/2/toggle');

        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertIsObject($task);

        $this->assertIsBool($task->isDone());
        $this->assertFalse($task->isDone());
    }

    public function testSuccessDeleteTask(): void
    {
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertIsObject($task);
        $this->assertNotNull($task->getId());
        
        $this->client->request('GET', '/tasks/2/edit');
        
        $this->assertResponseRedirects('/tasks');
        $this->assertSelectorExists('.alert.alert-success');
        
        $taksRepository = static::getContainer()->get(TaskRepository::class);

        $task = $taksRepository->find(2);

        $this->assertNull($task);
    }
}

