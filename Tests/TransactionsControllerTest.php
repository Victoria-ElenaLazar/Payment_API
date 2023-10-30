<?php
declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use PaymentApi\Controllers\Basket\BasketController;
use PaymentApi\Controllers\Customers\CustomersController;
use PaymentApi\Controllers\PaymentMethods\PaymentMethodsController;
use PaymentApi\Controllers\Transactions\TransactionsController;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\BasketRepositoryDoctrine;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepository;
use PaymentApi\Repositories\PaymentMethodsRepositoryDoctrine;
use PaymentApi\Repositories\TransactionRepository;
use PaymentApi\Repositories\TransactionRepositoryDoctrine;
use PhpUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;


class TransactionsControllerTest extends TestCase
{
    private Container $container;

    public function setUp(): void
    {
        $container = new Container();
        $container->set(EntityManager::class, function (Container $container) {
            return Mockery::mock('Doctrine\ORM\EntityManager');
        });

        $container->set(TransactionRepository::class, function (Container $container) {
            $entityManager = $container->get(EntityManager::class);
            return new TransactionRepositoryDoctrine($entityManager);
        });

        $container->set(CustomersRepository::class, function (Container $container) {
            $entityManager = $container->get(EntityManager::class);
            return new CustomersRepositoryDoctrine($entityManager);
        });

        $container->set(BasketRepository::class, function (Container $container) {
            $entityManager = $container->get(EntityManager::class);
            return new BasketRepositoryDoctrine($entityManager);
        });

        $container->set(PaymentMethodsRepository::class, function (Container $container) {
            $entityManager = $container->get(EntityManager::class);
            return new PaymentMethodsRepositoryDoctrine($entityManager);
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
    public function testCreateInstanceOfTransactionsController()
    {
        $transaction = new TransactionsController($this->container);
        $customer = new CustomersController($this->container);
        $basket = new BasketController($this->container);
        $paymentMethod = new PaymentMethodsController($this->container);
        $this->assertInstanceOf('PaymentApi\Controllers\Transactions\TransactionsController', $transaction);
        $this->assertInstanceOf('PaymentApi\Controllers\Customers\CustomersController', $customer);
        $this->assertInstanceOf('PaymentApi\Controllers\Basket\BasketController', $basket);
        $this->assertInstanceOf('PaymentApi\Controllers\PaymentMethods\PaymentMethodsController', $paymentMethod);
    }
}