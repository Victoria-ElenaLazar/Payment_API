<?php
declare(strict_types=1);

namespace PaymentApi\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PaymentApi\Models\A_Model;
#[ORM\Entity, ORM\Table(name: 'customers')]
class Customers extends A_Model
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;
    #[ORM\Column(type: 'string', nullable: false)]
    private string $address;
    #[ORM\Column(name: 'is_active', type: 'boolean', nullable: false)]
    private bool $isActive;
    #[ORM\OneToMany(mappedBy: "customer", targetEntity: Transactions::class)]
    private Collection $transaction;
    #[ORM\OneToMany(mappedBy: "basket", targetEntity: Basket::class)]
    private Collection $basket;

    public function __construct()
    {
        parent::__construct();
        $this->transaction = new ArrayCollection();
        $this->basket = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return Collection
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    /**
     * @param Collection $transaction
     */
    public function setTransaction(Collection $transaction): void
    {
        $this->transaction = $transaction;
    }

    /**
     * @return Collection
     */
    public function getBasket(): Collection
    {
        return $this->basket;
    }

    /**
     * @param Collection $basket
     */
    public function setBasket(Collection $basket): void
    {
        $this->basket = $basket;
    }

}