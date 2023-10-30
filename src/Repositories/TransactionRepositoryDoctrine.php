<?php

namespace PaymentApi\Repositories;

use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Models\Customers;
use PaymentApi\Models\Transactions;

class TransactionRepositoryDoctrine extends A_Repository implements TransactionRepository

{
    /**
     * @return array
     * @throws NotSupported
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Transactions::class)->findAll();

    }

    /**
     * @param int $transactionId
     * @return Customers|null
     * @throws NotSupported
     */
    public function findById(int $transactionId): Transactions|null
    {
        return $this->entityManager->getRepository(Transactions::class)->find($transactionId);
    }
}