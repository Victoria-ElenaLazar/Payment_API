<?php
declare(strict_types=1);
error_log("Message");
global $container;

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use PaymentApi\Middlewares\BeforeMiddleware;
use PaymentApi\Middlewares\CustomErrorHandler;


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Container/container.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();


$app = AppFactory::create(container: $container);

$app->group('/v1/user', function (RouteCollectorProxy $group) {
    $group->post('/registration', '\PaymentApi\Controllers\Users\UsersController:registration');
    $group->get('/apidocs', '\PaymentApi\Controllers\OpenAPIController:documentationAction');
});

$app->group('/v1/payment-method', function (RouteCollectorProxy $group) {
    $group->get('', '\PaymentApi\Controllers\PaymentMethods\PaymentMethodsController:indexAction');
    $group->post('', '\PaymentApi\Controllers\PaymentMethods\CreatePaymentMethodController:createAction');
    $group->put('/{id:[0-9]+}', '\PaymentApi\Controllers\PaymentMethods\UpdatePaymentMethodController:updateAction');
    $group->get('/deactivate/{id:[0-9]+}', '\PaymentApi\Controllers\PaymentMethods\DeactivatePaymentMethodController:deactivateAction');
    $group->get('/reactivate/{id:[0-9]+}', '\PaymentApi\Controllers\PaymentMethods\ReactivatePaymentMethodController:reactivateAction');
    $group->delete('/{id:[0-9]+}', '\PaymentApi\Controllers\PaymentMethods\DeletePaymentMethodController:deleteAction');
    $group->get('/apidocs', '\PaymentApi\Controllers\OpenAPIController:documentationAction');

})->add(new BeforeMiddleware($container));

$app->group('/v1/customer', function (RouteCollectorProxy $group) {
    $group->get('', '\PaymentApi\Controllers\Customers\CustomersController:indexAction');
    $group->post('', '\PaymentApi\Controllers\Customers\CreateCustomerController:createAction');
    $group->put('/{id:[0-9]+}', '\PaymentApi\Controllers\Customers\UpdateCustomerController:updateAction');
    $group->get('/deactivate/{id:[0-9]+}', '\PaymentApi\Controllers\DeactivateCustomerController:deactivateAction');
    $group->get('/reactivate/{id:[0-9]+}', '\PaymentApi\Controllers\Customers\ReactivateCustomerController:reactivateAction');
    $group->delete('/{id:[0-9]+}', '\PaymentApi\Controllers\Customers\DeleteCustomerController:deleteAction');
    $group->get('/apidocs', '\PaymentApi\Controllers\OpenAPIController:documentationAction');

})->add(new BeforeMiddleware($container));

$app->group('/v1/transaction', function (RouteCollectorProxy $group) {
    $group->get('', '\PaymentApi\Controllers\Transactions\TransactionsController:indexAction');
    $group->post('', '\PaymentApi\Controllers\Transactions\CreateTransactionController:createAction');
    $group->put('/{id:[0-9]+}', '\PaymentApi\Controllers\Transactions\UpdateTransactionController:updateAction');
    $group->get('/deactivate/{id:[0-9]+}', '\PaymentApi\Controllers\Transactions\DeactivateTransactionController:deactivateAction');
    $group->get('/reactivate/{id:[0-9]+}', '\PaymentApi\Controllers\Transactions\ReactivateTransactionController:reactivateAction');
    $group->delete('/{id:[0-9]+}', '\PaymentApi\Controllers\Transactions\DeleteTransactionController:deleteAction');
    $group->get('/apidocs', '\PaymentApi\Controllers\OpenAPIController:documentationAction');

})->add(new BeforeMiddleware($container));

$app->group('/v1/basket', function (RouteCollectorProxy $group) {
    $group->get('', '\PaymentApi\Controllers\Basket\BasketController:indexAction');
    $group->get('/{id:[0-9]+}', '\PaymentApi\Controllers\Basket\BasketController:getAction');
    $group->post('', '\PaymentApi\Controllers\Basket\CreateBasketController:createAction');
    $group->put('/{id:[0-9]+}', '\PaymentApi\Controllers\Basket\UpdateBasketController:updateAction');
    $group->get('/deactivate/{id:[0-9]+}', '\PaymentApi\Controllers\Basket\DeactivateBasketController:deactivateAction');
    $group->get('/reactivate/{id:[0-9]+}', '\PaymentApi\Controllers\Basket\ReactivateBasketController:reactivateAction');
    $group->delete('/{id:[0-9]+}', '\PaymentApi\Controllers\Basket\DeleteBasketController:deleteAction');
    $group->get('/apidocs', '\PaymentApi\Controllers\OpenAPIController:documentationAction');

})->add(new BeforeMiddleware($container));


$displayErrors = $_ENV['APP_ENV'] != 'production';


$customErrorHandler = new CustomErrorHandler($app);


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();