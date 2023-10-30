<?php
declare(strict_types=1);

namespace PaymentApi\Models;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'users')]
class Users extends A_Model
{
    #[ORM\Id, ORM\Column(type: 'integer', nullable: false), ORM\GeneratedValue('AUTO')]
    private int $id;
    #[ORM\Column(name: 'name', type: 'string', nullable: false)]
    private string $name;
    #[ORM\Column(name: 'address', type: 'string', nullable: false)]
    private string $address;
    #[ORM\Column(name: 'email', type: 'string', nullable: false)]
    private string $email;
    #[ORM\Column(name: 'date_of_birth', type: 'string', nullable: false)]
    private string $birthDate;
    #[ORM\Column(name: 'password', type: 'string', nullable: false)]
    private string $password;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    /**
     * @param string $birthDate
     */
    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}