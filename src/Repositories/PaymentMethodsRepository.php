<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use PaymentApi\Models\PaymentMethods;

interface PaymentMethodsRepository
{
    public function store(PaymentMethods $paymentMethods): void;

    public function update(PaymentMethods $paymentMethods): void;

    public function remove(PaymentMethods $paymentMethods): void;

    public function findAll(): array;

    public function findById(int $paymentMethodsId): PaymentMethods|null;

    public function findPaymentMethodByName(string $name): ?PaymentMethods;
    public function findPaymentMethodIdByName(string $name): int;

}