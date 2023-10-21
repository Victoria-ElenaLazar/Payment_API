<?php
declare(strict_types=1);

use PaymentApi\Repositories\UsersRepository;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\TransactionRepository;
use PaymentApi\Repositories\UsersRepositoryDoctrine;
use PaymentApi\Repositories\BasketRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PaymentApi\Repositories\TransactionRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepositoryDoctrine;

use DI\Container;
use Dotenv\Dotenv;
use Monolog\Level;
use Monolog\Logger;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$container = new Container;

const APP_ROOT = __DIR__ . '/../';
$container->set('settings', function ($container) {
    return [
        'displayErrors' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => APP_ROOT . '/var/doctrine',
            'metadata_dirs' => [APP_ROOT . '/src'],
            'connection' => [
                'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
                'host' => $_ENV['MARIADB_HOST'] ?? 'localhost',
                'port' => 3306,
                'dbname' => $_ENV['MARIADB_DATABASE'] ?? 'mydb',
                'user' => $_ENV['MARIADB_USER'] ?? 'user',
                'password' => $_ENV['MARIADB_PASSWORD'] ?? 'password'
            ]
        ]
    ];
});

$container->set(EntityManager::class, function (Container $container): EntityManager {
    $settings = $container->get('settings');

    $cache = $settings['doctrine']['dev_mode'] ?
        DoctrineProvider::wrap(new ArrayAdapter()) :
        DoctrineProvider::wrap(new FilesystemAdapter(directory: $settings['doctrine']['cache_dir']));
    $config = Setup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode'],
        null,
        $cache
    );
    return EntityManager::create($settings['doctrine']['connection'], $config);

});

$container->set(PaymentMethodsRepository::class, function (Container $container) {
    $entityManager = $container->get(EntityManager::class);
    return new PaymentMethodsRepositoryDoctrine($entityManager);
});

$container->set(CustomersRepository::class, function (Container $container) {
    $entityManager = $container->get(EntityManager::class);
    return new CustomersRepositoryDoctrine($entityManager);
});

$container->set(TransactionRepository::class, function (Container $container) {
    $entityManager = $container->get(EntityManager::class);
    return new TransactionRepositoryDoctrine($entityManager);
});

$container->set(BasketRepository::class, function (Container $container) {
    $entityManager = $container->get(EntityManager::class);
    return new BasketRepositoryDoctrine($entityManager);
});

$container->set(UsersRepository::class, function (Container $container) {
    $entityManager = $container->get(EntityManager::class);
    return new UsersRepositoryDoctrine($entityManager);
});

$container->set(Logger::class, function (Container $container) {
    $logger = new logger('paymentApi');
    $output = "%level_name% | %datetime% > %message% | %context% %extra%\n ";
    $dateFormat = "Y-m-d, H:i:s";
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/alert.log', Level::Alert))->setFormatter(new LineFormatter($output, $dateFormat)));
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/critical.log', Level::Critical))->setFormatter(new LineFormatter($output, $dateFormat)));
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/error.log', Level::Error))->setFormatter(new LineFormatter($output, $dateFormat)));
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/warning.log', Level::Warning))->setFormatter(new LineFormatter($output, $dateFormat)));
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/notice.log', Level::Notice))->setFormatter(new LineFormatter($output, $dateFormat)));
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/info.log', Level::Info))->setFormatter(new LineFormatter($output, $dateFormat)));
    $logger->pushHandler((new StreamHandler(__DIR__ . '/../logs/debug.log', Level::Debug))->setFormatter(new LineFormatter($output, $dateFormat)));
    return $logger;
});

return $container;
