<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Models\Basket;

class BasketRepositoryDoctrine extends A_Repository implements BasketRepository
{
    /**
     * @return array
     * @throws NotSupported
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Basket::class)->findAll();

    }

    /**
     * @param int $basketId
     * @return Basket|null
     * @throws NotSupported
     */
    public function findById(int $basketId): Basket|null
    {
        return $this->entityManager->getRepository(Basket::class)->find($basketId);
    }


}