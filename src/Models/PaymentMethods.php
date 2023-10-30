<?php
declare(strict_types=1);

namespace PaymentApi\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PaymentApi\Models\A_Model;

#[ORM\Entity, ORM\Table(name: 'payment_methods')]
class PaymentMethods extends A_Model
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\Column(type: 'string', unique: true, nullable: false)]
    private string $name;
    #[ORM\Column(name: 'is_active', type: 'boolean', nullable: false)]
    private bool $isActive;
    #[ORM\OneToMany(mappedBy: "payment_method", targetEntity: Transactions::class)]
    private Collection $paymentMethod;

    public function __construct()
    {
        parent::__construct();
        $this->paymentMethod = new ArrayCollection();
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
    public function getPaymentMethod(): Collection
    {
        return $this->paymentMethod;
    }

    /**
     * @param Collection $paymentMethod
     */
    public function setPaymentMethod(Collection $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

}