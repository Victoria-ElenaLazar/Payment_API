<?php
declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use PaymentApi\Controllers\Customers\CustomersController;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;


class CustomersControllerTest extends TestCase
{
    private Container $container;

    public function setUp(): void
    {
        $container = new Container();
        $container->set(EntityManager::class, function (Container $c) {
            return Mockery::mock('Doctrine\ORM\EntityManager');
        });

        $container->set(CustomersRepository::class, function (Container $container) {
            $entityManager = $container->get(EntityManager::class);
            return new CustomersRepositoryDoctrine($entityManager);
        });

        $container->set(Logger::class, function (Container $container) {
            return Mockery::mock('Monolog\Logger');
        });

        $this->container = $container;
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCreateInstanceOfPaymentMethodsController()
    {
        $customer = new CustomersController($this->container);
        $this->assertInstanceOf('PaymentApi\Controllers\Customers\CustomersController', $customer);
    }
}