<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $price = null;

    /**
     * @var Collection<int, Purchase>
     */
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'ticket')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Collection $Purchase;

    #[ORM\Column(length: 255)]
    private ?string $description = null;



    public function __construct()
    {
        $this->Purchase = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchase(): Collection
    {
        return $this->Purchase;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->Purchase->contains($purchase)) {
            $this->Purchase->add($purchase);
            $purchase->setTicket($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->Purchase->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getTicket() === $this) {
                $purchase->setTicket(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


}
