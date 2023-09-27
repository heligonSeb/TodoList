<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase 
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

    public function testDisplayLogin(): void
    {
        $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', "Please sign in");
        $this->assertSelectorNotExists('.alert.alert-danger');
    }


    public function testLoginPageWithBadCredentials(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'toto',
            'password' => 'fakepassword'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLoginLoginPage(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form([
            'username' => 'user0',
            'password' => 'password'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('');
    }


    public function testSuccessfullLogin(): void 
    {
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('user@user.user');

        $this->client->loginUser($testUser);

        $this->client->request('POST', '/login');
        $this->assertResponseRedirects('/login');
    }
}
