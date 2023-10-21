<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PaymentApi\Models\A_Model;

abstract class A_Repository
{
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(protected EntityManager $entityManager)
    {
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function store(A_Model $model): void
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update(A_Model $model): void
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(A_Model $model): void
    {
        $this->entityManager->remove($model);
        $this->entityManager->flush();
    }
}