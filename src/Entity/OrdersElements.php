<?php

namespace App\Entity;

use App\Repository\OrdersElementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersElementsRepository::class)]
class OrdersElements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordersElements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Orders $mainOrder = null;

    #[ORM\ManyToOne(inversedBy: 'ordersElements')]
    private ?Menu $menu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainOrder(): ?Orders
    {
        return $this->mainOrder;
    }

    public function setMainOrder(?Orders $mainOrder): static
    {
        $this->mainOrder = $mainOrder;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): static
    {
        $this->menu = $menu;

        return $this;
    }
}
