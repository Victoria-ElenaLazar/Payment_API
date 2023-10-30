<?php
declare(strict_types=1);

namespace PaymentApi\Repositories;

use PaymentApi\Models\PaymentMethods;
use Doctrine\ORM\Exception\NotSupported;

class PaymentMethodsRepositoryDoctrine extends A_Repository implements PaymentMethodsRepository
{

    /**
     * @throws NotSupported
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(PaymentMethods::class)->findAll();
    }

    /**
     * @throws NotSupported
     */
    public function findById(int $paymentMethodsId): PaymentMethods|null
    {
        return $this->entityManager->getRepository(PaymentMethods::class)->find($paymentMethodsId);
    }

    /**
     * @param string $name
     * @return PaymentMethods|null
     * @throws NotSupported
     */
    public function findPaymentMethodByName(string $name): ?PaymentMethods
    {
        return $this->entityManager->getRepository(PaymentMethods::class)->findOneBy(['name' => $name]);
    }

    /**
     * @throws NotSupported
     */
    public function findPaymentMethodIdByName(string $name): int
    {
        $paymentMethod = $this->entityManager->getRepository(PaymentMethods::class)->findOneBy(['name' => $name]);
        if ($paymentMethod){
            return $paymentMethod->getId();
        }
        return 0;
    }

}