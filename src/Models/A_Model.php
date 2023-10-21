<?php
declare(strict_types=1);

namespace PaymentApi\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

abstract class A_Model
{
    protected EntityManagerInterface $entityManager;

    public function __construct(){}


    /**
     * Get the unique identifier for the model.
     *
     * @return int
     */
    abstract public function getId(): int;


    /**
     * Store the model in the database.
     */
    public function store(A_Model $model): void
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }

    /**
     * Update the model in the database.
     */
    public function update(A_Model $model): void
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }

    /**
     * Delete the model from the database.
     */
    public function delete(): void
    {
        $this->entityManager->remove($this);
        $this->entityManager->flush();
    }
}
