<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {
        $user = new User();
        $user->setUsername("userTest");
        $user->setEmail("test@email.com");
        $user->setPassword("testPassword");
        $user->setRoles(["ROLE_USER"]);
        return $user;
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $this->assertCount($number, $errors);
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }
    public function testInvalidEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('email'), 1);
    }
    public function testInvalidRoleEntity()
    {
        $this->assertHasErrors($this->getEntity()->setRoles(['']), 0);
    }
    public function testInvalidPasswordEntity()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }
    public function testInvalidIdEntity()
    {
        $this->assertEmpty($this->getEntity()->getId());
    }
    public function TestTask()
    {

        $task = new Task();

        $user = $this->getEntity()->addTask($task);
        $this->assertInstanceOf(ArrayCollection::class, $user->getTasks());
        $this->assertNotEmpty($user->getTasks());
        $user->removeTask($task);
        $this->assertEmpty($user->getTasks());
        $this->assertEquals($task, $user->getTasks());
        $this->assertHasErrors($this->getEntity()->addTask(''), 1);
    }
    public function testEquals(): void
    {
        $task = new Task();
        $user = $this->getEntity();
        $this->assertEquals("userTest", $user->getUsername());
        $this->assertEquals("userTest", $user->getUserIdentifier());
        $this->assertEquals("test@email.com", $user->getEmail());
        $this->assertEquals('testPassword', $user->getPassword());
        $this->assertEquals(["ROLE_USER"], $user->getRoles());
    }
}
