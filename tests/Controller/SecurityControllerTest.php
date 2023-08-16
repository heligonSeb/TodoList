<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase 
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
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
        $this->assertResponseRedirects('/');
    }


    public function testSuccessfullLogin(): void 
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('user1@domain.fr');

        $this->client->loginUser($testUser);

        $this->client->request('POST', '/login');
        $this->assertResponseRedirects('/login');
    }
}
