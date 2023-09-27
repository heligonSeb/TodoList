<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class UserControllerTest extends WebTestCase
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

    public function testDisplayUser(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@admin.admin');

        $this->client->loginUser($testUser);
        
        $this->client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testDisplayCreateUser(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@admin.admin');

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/users/create');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessCreateUser(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@admin.admin');

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user11',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user11@domain.fr'
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects("/users");
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDisplayEditUser(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@admin.admin');

        $this->client->loginUser($testUser);
        
        $this->client->request('GET', '/users/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testSuccessEditUser(): void
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@admin.admin');

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/users/2/edit');
        
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'user11',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user11@domain.fr'
        ]);
        
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        $task = $userRepository->find(2);

        $this->assertNotNull($task->getId());
        $this->assertSame("user11", $task->getUsername());
    }
}