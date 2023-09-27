<?php

namespace App\Tests\Controller;

use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    /** 
     * @var AbstractDatabaseTool 
     */
    protected $databaseTool;

    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
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
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('user@user.user');

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessCreateTask(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('user@user.user');

        $this->client->loginUser($testUser);

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
        $this->databaseTool->loadFixtures([TaskFixtures::class, UserFixtures::class]);

        $taskRepo = static::getContainer()->get(TaskRepository::class);
        $taskTest = $taskRepo->findOneById(2);

        $testUser = $taskTest->getUser();

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/tasks/2/edit');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessEditTask(): void
    {
        $this->databaseTool->loadFixtures([TaskFixtures::class, UserFixtures::class]);

        $taskRepo = static::getContainer()->get(TaskRepository::class);
        $taskTest = $taskRepo->findOneById(2);

        $testUser = $taskTest->getUser();

        $this->client->loginUser($testUser);

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
        $this->databaseTool->loadFixtures([TaskFixtures::class, UserFixtures::class]);

        $taskRepo = static::getContainer()->get(TaskRepository::class);
        $taskTest = $taskRepo->findOneById(2);

        $testUser = $taskTest->getUser();

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/tasks/2/delete');

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testToggleTask(): void
    {
        $this->databaseTool->loadFixtures([TaskFixtures::class, UserFixtures::class]);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@user.user');

        $this->client->loginUser($testUser);

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
        $this->databaseTool->loadFixtures([TaskFixtures::class, UserFixtures::class]);

        $taskRepo = static::getContainer()->get(TaskRepository::class);
        $taskTest = $taskRepo->findOneById(3);

        $testUser = $taskTest->getUser();

        $this->client->loginUser($testUser);
        
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

