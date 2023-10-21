<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use PaymentApi\Models\Users;

interface UsersRepository
{
    public function store(Users $users): void;

    public function update(Users $users): void;

    public function remove(Users $users): void;

    public function findAll(): array;

    public function findById(int $userId): Users|null;

    public function findUserByName(string $name): ?Users;
    public function findUserIdByName(string $name): int;
    public function findUserByEmail(string $email): ?Users;
}