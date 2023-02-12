<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $users = ["admin", "user1", "user2"];
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        foreach ($this->users as $value) {
            $user = new User();
            $user->setEmail($value . '@email.com')
                ->setUsername($value);
            if ($value === "admin") {
                $user->setRoles(["ROLE_ADMIN"]);
            } else {
                $user->setRoles(["ROLE_USER"]);
            }
            $password = $this->passwordHasher->hashPassword($user, 'Secret');
            $user->setPassword($password);
            $manager->persist($user);
            // create tasks
            for ($count = 0; $count < 4; $count++) {
                $task = new Task();
                $task->setTitle("task" . $count)
                    ->setContent("to do as fast as possible")
                    ->setUser($user);

                $manager->persist($task);
            }
        }
        $manager->flush();
    }
}
