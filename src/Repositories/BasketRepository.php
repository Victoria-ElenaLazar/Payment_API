<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;
use PaymentApi\Models\Basket;

interface BasketRepository
{
    public function store(Basket $basket): void;

    public function update(Basket $basket): void;

    public function remove(Basket $basket): void;

    public function findAll(): array;

    public function findById(int $basketId): Basket|null;
}