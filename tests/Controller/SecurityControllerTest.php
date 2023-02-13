<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityControllerTest extends WebTestCase
{
    public function testLogout(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'admin']);

        if (!$testUser instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Se déconnecter')->link();
        $client->click($link);
        $this->assertTrue($client->getResponse()->isRedirection());

        $client->followRedirect();

        $this->assertStringContainsString('Se connecter', '' . $client->getResponse()->getContent());
    }
    public function testLogin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form(array(
            '_username' => 'admin',
            '_password' => 'Secret',
        ));

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();

        // $this->assertStringContainsString('Se déconnecter', '' . $client->getResponse()->getContent());
    }
}
