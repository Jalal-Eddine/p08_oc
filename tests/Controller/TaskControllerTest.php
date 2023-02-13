<?php

namespace Tests\Controller;

use App\Entity\Task;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskControllerTest extends WebTestCase
{
    public function logUserTest(string $username, KernelBrowser $client): void
    {

        $userRepository = static::$container->get(UserRepository::class);

        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user instanceof UserInterface) {
            throw new Exception("Il n'y a pas de testUser pour se connecter", 1);
        }

        $client->loginUser($user);
    }


    public function findTask(array $criteria): Task
    {

        $taskRepository = static::$container->get(TaskRepository::class);

        $task = $taskRepository->findOneBy($criteria);
        if (!$task instanceof Task) {
            throw new Exception("La task de test n'existe pas", 1);
        }
        return $task;
    }

    public function testCreateTask(): void
    {
        $client = static::createClient();
        $this->logUserTest('admin', $client);

        //Test formulaire valide
        $crawler = $client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form(array(
            'task[title]' => 'Test create title',
            'task[content]' => 'Test create content',
        ));
        $crawler = $client->submit($form);
        $client->followRedirect();
        $this->assertStringContainsString('Test create', '' . $client->getResponse()->getContent());
        $this->assertStringContainsString('La tâche a été bien été ajoutée', '' . $client->getResponse()->getContent());

        //Test nouvelle tâche sur la page
        $task = $this->findTask(['title' => 'Test create Title']);
        $this->assertEquals('Test create title', $task->getTitle());

        //Test CreatedAt
        $task = $this->findTask(['id' => $task->getId()]);
        $now = new DateTime();
        $taskDate = $task->getCreatedAt();
        if (!$taskDate instanceof DateTime) {
            throw new Exception("Erreur sur la date de création de test", 1);
        }
        $interval = $now->diff($taskDate);
        $nbJour = $interval->h;
        $this->assertLessThanOrEqual('1', $nbJour, 'La date de création est fausse');
    }
    public function testEditTask(): void
    {
        $client = static::createClient();
        $this->logUserTest('admin', $client);

        $crawler = $client->request('GET', '/tasks/7/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Modifier', '' . $client->getResponse()->getContent());

        $form = $crawler->selectButton('Modifier')->form(array(
            'task[title]' => 'Test modif Title',
            'task[content]' => 'TestAuto content',
        ));
        $crawler = $client->submit($form);
        $client->followRedirect();
        $this->assertStringContainsString('Test modif Title', '' . $client->getResponse()->getContent());
        $this->assertStringContainsString('La tâche a bien été modifiée.', '' . $client->getResponse()->getContent());
    }
    public function testToggleTask(): void
    {
        $client = static::createClient();
        $this->logUserTest('admin', $client);

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler
            ->selectButton('Marquer comme faite')
            ->eq(1)
            ->form();
        $crawler = $client->submit($form);
        $client->followRedirect();
        $this->assertStringContainsString('a bien été marquée comme faite', '' . $client->getResponse()->getContent());

        $task = $this->findTask(['id' => '11']);
        $this->assertEquals(true, $task->IsDone());
    }
    public function testDeleteTask(): void
    {
        $client = static::createClient();
        $this->logUserTest('admin', $client);

        $crawler = $client->request('GET', '/tasks');
        $form = $crawler
            ->selectButton('Supprimer')
            ->eq(2)
            ->form();
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());

        $client->followRedirect();
        $this->assertStringContainsString('La tâche a bien été supprimée.', '' . $client->getResponse()->getContent());
    }
    public function testDeleteTasknotCreated(): void
    {
        $client = static::createClient();
        $this->logUserTest('user1', $client);

        $task = $this->findTask(['id' => '24']);
        $this->assertNotEmpty($task, 'La tâche à supprimer n\'existe pas');

        $client->request('GET', '/tasks/24/delete');
        $this->assertFalse($client->getResponse()->isForbidden());
    }
}
