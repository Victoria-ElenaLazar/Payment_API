<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use PaymentApi\Models\Customers;

interface CustomersRepository
{
    public function store(Customers $customers): void;

    public function update(Customers $customers): void;

    public function remove(Customers $customers): void;

    public function findAll(): array;

    public function findById(int $customerId): Customers|null;

    public function findCustomerByName(string $name): ?Customers;
    public function findCustomerIdByName(string $name): int;
}