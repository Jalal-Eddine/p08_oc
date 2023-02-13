<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntityUser(): User
    {
        $user = new User();
        $user->setUsername("testUsername");
        $user->setEmail("test@email.com");
        $user->setPassword("testPassword");
        $user->setRoles(["ROLE_USER"]);
        return $user;
    }
    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle('task title')
            ->setContent('this is a task content')
            ->setCreatedAt(new \DateTime())
            ->setIsDone(false)
            ->setUser($this->getEntityUser());
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($task);
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
        $this->assertEmpty($this->getEntity()->getId(), 0);
    }
    public function testInvalidEntity()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testEquals(): void
    {
        $task = $this->getEntity();
        $newDate = new DateTime(date('2020-01-01 00:00:00'));
        $this->assertEquals(false, $task->IsDone());
        $this->assertEquals('task title', $task->getTitle());
        $this->assertEquals('this is a task content', $task->getContent());
        $task->setCreatedAt($newDate);
        $this->assertEquals($newDate, $task->getCreatedAt());
        $this->assertEquals($this->getEntityUser(), $task->getUser());
        $task->toggle(true);
        $this->assertEquals(true, $task->IsDone());
    }
}
