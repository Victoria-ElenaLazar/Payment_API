<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;


use PaymentApi\Models\Transactions;

interface TransactionRepository
{
    public function store(Transactions $transactions): void;

    public function update(Transactions $transactions): void;

    public function remove(Transactions $transactions): void;

    public function findAll(): array;

    public function findById(int $transactionId): Transactions|null;
}