<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Transactions;

use Doctrine\ORM\Exception\NotSupported;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Models\Transactions;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\BasketRepositoryDoctrine;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepository;
use PaymentApi\Repositories\PaymentMethodsRepositoryDoctrine;
use PaymentApi\Repositories\TransactionRepository;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;


class TransactionsController extends A_Controller
{
    private PaymentMethodsRepositoryDoctrine $paymentMethodsRepository;
    private CustomersRepositoryDoctrine $customerRepository;
    private BasketRepositoryDoctrine $basketRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(TransactionRepository::class);
        $this->basketRepository = $container->get(BasketRepository::class);
        $this->customerRepository = $container->get(CustomersRepository::class);
        $this->paymentMethodsRepository = $container->get(PaymentMethodsRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::Transaction;
        $this->routeValue = Routes::Transaction->value;
    }

    /**
     * @OA\Get(
     *     path="/v1/transaction",
     *     description="Returns all transactions",
     *     @OA\Response(
     *          response=200,
     *          description="Transaction response",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *   )
     * )
     * @param Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotSupported
     */

    public function indexAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        return parent::indexAction($request, $response);
    }
}