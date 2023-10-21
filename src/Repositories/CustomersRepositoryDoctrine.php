<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Models\Customers;

class CustomersRepositoryDoctrine extends A_Repository implements CustomersRepository
{

    /**
     * @return array
     * @throws NotSupported
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Customers::class)->findAll();

    }

    /**
     * @param int $customerId
     * @return Customers|null
     * @throws NotSupported
     */
    public function findById(int $customerId): Customers|null
    {
        return $this->entityManager->getRepository(Customers::class)->find($customerId);
    }

    /**
     * @param string $name
     * @return Customers|null
     * @throws NotSupported
     */
    public function findCustomerByName(string $name): ?Customers
    {
        return $this->entityManager->getRepository(Customers::class)->findOneBy(['name' => $name]);
    }

    /**
     * @throws NotSupported
     */
    public function findCustomerIdByName(string $name): int
    {
        $customer = $this->entityManager->getRepository(Customers::class)->findOneBy(['name' => $name]);
        if ($customer){
            return $customer->getId();
        }
        return 0;
    }
}