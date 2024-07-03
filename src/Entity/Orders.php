<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bookings $booking = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $manager = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?OrderStatus $status = null;

    /**
     * @var Collection<int, OrdersElements>
     */
    #[ORM\OneToMany(targetEntity: OrdersElements::class, mappedBy: 'mainOrder')]
    private Collection $ordersElements;

    public function __construct()
    {
        $this->ordersElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooking(): ?Bookings
    {
        return $this->booking;
    }

    public function setBooking(?Bookings $booking): static
    {
        $this->booking = $booking;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function getStatus(): ?OrderStatus
    {
        return $this->status;
    }

    public function setStatus(?OrderStatus $status): static
    {
        $this->status = $status;

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
            $ordersElement->setMainOrder($this);
        }

        return $this;
    }

    public function removeOrdersElement(OrdersElements $ordersElement): static
    {
        if ($this->ordersElements->removeElement($ordersElement)) {
            // set the owning side to null (unless already changed)
            if ($ordersElement->getMainOrder() === $this) {
                $ordersElement->setMainOrder(null);
            }
        }

        return $this;
    }
}
