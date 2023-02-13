<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testValidEntity()
    {
        $code = (new Task())
            ->setTitle('task title')
            ->setContent('this is a task content');
        self::bootKernel();
        $error = self::$container->get('validator')->validate($code);
        $this->assertCount(0, $error);
    }
}
