<?php
declare(strict_types=1);

use DI\Container;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use PaymentApi\Controllers\PaymentMethods\PaymentMethodsController;
use PaymentApi\Models\PaymentMethods;
use PaymentApi\Repositories\PaymentMethodsRepository;
use PaymentApi\Repositories\PaymentMethodsRepositoryDoctrine;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaymentMethodsControllerTest extends TestCase
{
    private Container $container;

    /**
     * @return void
     *set up test environment
     */
    public function setUp(): void
    {
        $container = new Container();
        $container->set(EntityManager::class, function (Container $c) {
            return Mockery::mock('Doctrine\ORM\EntityManager');
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
     * test the entire class Payment Methods Controller
     */
    public function testCreateInstanceOfPaymentMethodsController()
    {
        $paymentMethod = new PaymentMethodsController($this->container);
        $this->assertInstanceOf('PaymentApi\Controllers\PaymentMethods\PaymentMethodsController', $paymentMethod);
    }

    /**
     * @return void
     * test if the create function works properly
     */
    public function testCreateAction()
    {
        $paymentMethod = new PaymentMethods();
        $paymentMethod->setName('Online payment');
        $this->assertEquals('Online payment', $paymentMethod->getName());
    }
}
