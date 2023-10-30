<?php

namespace PaymentApi\Repositories;

use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Models\A_Model;
use PaymentApi\Models\Users;

class UsersRepositoryDoctrine extends A_Repository implements UsersRepository
{
    /**
     * @return array
     * @throws NotSupported
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Users::class)->findAll();

    }

    /**
     * @param int $userId
     * @return Users|null
     * @throws NotSupported
     */
    public function findById(int $userId): Users|null
    {
        return $this->entityManager->getRepository(Users::class)->find($userId);
    }

    /**
     * @param string $name
     * @return Users|null
     * @throws NotSupported
     */
    public function findUserByName(string $name): ?Users
    {
        return $this->entityManager->getRepository(Users::class)->findOneBy(['name' => $name]);
    }

    /**
     * @throws NotSupported
     */
    public function findUserIdByName(string $name): int
    {
        $user = $this->entityManager->getRepository(Users::class)->findOneBy(['name' => $name]);
        if ($user){
            return $user->getId();
        }
        return 0;
    }

    /**
     * @param string $email
     * @return Users|null
     * @throws NotSupported
     */
    public function findUserByEmail(string $email): ?Users
    {
        return $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);
    }

}