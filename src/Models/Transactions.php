<?php
declare(strict_types=1);

namespace PaymentApi\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'transactions')]
class Transactions extends A_Model
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'payment_method_id', type: 'integer', nullable: false)]
    private int $paymentMethodId;
    #[ORM\Column(name: 'payment_method_name', type: 'string', nullable: false)]
    private string $paymentMethodName;

    #[ORM\Column(name: 'customer_id', type: 'integer', nullable: false)]
    private int $customerId;
    #[ORM\Column(name: 'customer_name', type: 'string', nullable: false)]
    private string $customerName;

    #[ORM\Column(name: 'basket_id', type: 'integer', nullable: false)]
    private int $basketId;

    #[ORM\Column(name: 'amount', type: 'float', nullable: false)]
    private float $amount;

    #[ORM\Column(name: 'sent', type: 'boolean', nullable: false)]
    private bool $sent;

    #[ORM\Column(name: 'transaction_date', type: 'string', nullable: false)]
    private string $transactionDate;

    #[ORM\ManyToOne(targetEntity: Customers::class, inversedBy: "transactions")]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id")]
    private Customers $customer;

    #[ORM\ManyToOne(targetEntity: PaymentMethods::class, inversedBy: "transactions")]
    #[ORM\JoinColumn(name: "payment_method_id", referencedColumnName: "id")]
    private PaymentMethods $paymentMethod;

    #[ORM\ManyToOne(targetEntity: Basket::class, inversedBy: "transactions")]
    #[ORM\JoinColumn(name: "basket_id", referencedColumnName: "id")]
    private Basket $basket;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPaymentMethodId(): int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(int $paymentMethodId): void
    {
        $this->paymentMethodId = $paymentMethodId;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getBasketId(): int
    {
        return $this->basketId;
    }

    public function setBasketId(int $basketId): void
    {
        $this->basketId = $basketId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function sent(): bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): void
    {
        $this->sent = $sent;
    }

    public function getTransactionDate(): string
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(string $transactionDate): void
    {
        $this->transactionDate = $transactionDate;
    }

    public function getCustomer(): Customers
    {
        return $this->customer;
    }

    public function setCustomer(Customers $customer): void
    {
        $this->customer = $customer;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }

    public function setBasket(Basket $basket): void
    {
        $this->basket = $basket;
    }

    /**
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     */
    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    /**
     * @return string
     */
    public function getPaymentMethodName(): string
    {
        return $this->paymentMethodName;
    }

    /**
     * @param string $paymentMethodName
     */
    public function setPaymentMethodName(string $paymentMethodName): void
    {
        $this->paymentMethodName = $paymentMethodName;
    }

    /**
     * @return PaymentMethods
     */
    public function getPaymentMethod(): PaymentMethods
    {
        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethods $paymentMethod
     */
    public function setPaymentMethod(PaymentMethods $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}