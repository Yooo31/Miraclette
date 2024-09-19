<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('Admin')
            ->setFirstName('Admin')
            ->setLastName('Admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'pass'));
        $manager->persist($user);
        $user = new User();
        $user->setUsername('Serveur')
            ->setFirstName('Serveur')
            ->setLastName('Serveur')
            ->setRoles(['Repos'])
            ->setPassword($this->hasher->hashPassword($user, 'pass'));
        $manager->persist($user);
        $user = new User();
        $user->setUsername('Cuisine')
            ->setFirstName('Cuisine')
            ->setLastName('Cuisine')
            ->setRoles(['Repos'])
            ->setPassword($this->hasher->hashPassword($user, 'pass'));
        $manager->persist($user);
        $user = new User();
        $user->setUsername('Bar')
            ->setFirstName('Bar')
            ->setLastName('Bar')
            ->setRoles(['Repos'])
            ->setPassword($this->hasher->hashPassword($user, 'pass'));
        $manager->persist($user);
        $manager->flush();
    }
}
