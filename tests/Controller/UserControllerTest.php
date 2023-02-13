<?php

namespace Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'admin']);

        if (!$testUser instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/users/create');

        $testUserRoles = $testUser->getRoles();
        $this->assertContains('ROLE_ADMIN', $testUserRoles, 'Le user connecté n\'est pas admin');

        $form = $crawler->selectButton('Ajouter')->form(array(
            'user[username]' => 'testUser5',
            'user[password][first]' => 'Secret',
            'user[password][second]' => 'Secret',
            'user[email]' => 'test5@email.com',
            'user[roles]' => 'ROLE_USER'
        ));

        $crawler = $client->submit($form);
        $client->getResponse()->isRedirection();
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();


        $this->assertStringContainsString('testUser5', '' . $client->getResponse()->getContent());
        $this->assertStringContainsString('utilisateur a bien été ajouté', '' . $client->getResponse()->getContent());
    }

    public function testEditUser(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => 'admin']);

        if (!$testUser instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/users/8/edit');

        $testUserRoles = $testUser->getRoles();
        $this->assertContains('ROLE_ADMIN', $testUserRoles, 'Le user connecté n\'est pas admin');

        $form = $crawler->selectButton('Modifier')->form(array(
            'user[username]' => 'modifTest',
            'user[password][first]' => 'Secret',
            'user[password][second]' => 'Secret',
            'user[email]' => 'janedoe@example.com',
            'user[roles]' => 'ROLE_USER'
        ));

        $crawler = $client->submit($form);

        $client->followRedirect();
        $this->assertStringContainsString('utilisateur a bien été modifié', '' . $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/users/8/edit');
        $this->assertStringContainsString('modifTest', '' . $client->getResponse()->getContent());
    }
}
