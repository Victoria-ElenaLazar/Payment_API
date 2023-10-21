<?php
declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use PaymentApi\Controllers\Basket\BasketController;
use PaymentApi\Controllers\Customers\CustomersController;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\BasketRepositoryDoctrine;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BasketControllerTest extends TestCase
{
    private Container $container;

    public function setUp(): void
    {
        $container = new Container();
        $container->set(EntityManager::class, function (Container $container) {
            return Mockery::mock('Doctrine\ORM\EntityManager');
        });

        $container->set(BasketRepository::class, function (Container $container) {
            $entityManager = $container->get(EntityManager::class);
            return new BasketRepositoryDoctrine($entityManager);
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testCreateInstanceOfBasketController()
    {
        $basket = new BasketController($this->container);
        $customer = new CustomersController($this->container);
        $this->assertInstanceOf('PaymentApi\Controllers\Basket\BasketController', $basket);
        $this->assertInstanceOf('PaymentApi\Controllers\Customers\CustomersController', $customer);

    }
}