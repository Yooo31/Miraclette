<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\OrderStatus;

class OrderStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $orderStatus = new OrderStatus();
        $orderStatus->setName('En cours');
        $manager->persist($orderStatus);
        $orderStatus->setName('Prête');
        $manager->persist($orderStatus);
        $orderStatus->setName('Terminée');
        $manager->persist($orderStatus);

        $manager->flush();
    }
}
