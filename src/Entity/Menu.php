<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?bool $available = null;

    /**
     * @var Collection<int, OrdersElements>
     */
    #[ORM\OneToMany(targetEntity: OrdersElements::class, mappedBy: 'menu')]
    private Collection $ordersElements;

    public function __construct()
    {
        $this->ordersElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return Collection<int, OrdersElements>
     */
    public function getOrdersElements(): Collection
    {
        return $this->ordersElements;
    }

    public function addOrdersElement(OrdersElements $ordersElement): static
    {
        if (!$this->ordersElements->contains($ordersElement)) {
            $this->ordersElements->add($ordersElement);
            $ordersElement->setMenu($this);
        }

        return $this;
    }

    public function removeOrdersElement(OrdersElements $ordersElement): static
    {
        if ($this->ordersElements->removeElement($ordersElement)) {
            // set the owning side to null (unless already changed)
            if ($ordersElement->getMenu() === $this) {
                $ordersElement->setMenu(null);
            }
        }

        return $this;
    }
}
