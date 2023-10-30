<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Transactions;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\TransactionRepository;
use PaymentApi\Repositories\BasketRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepositoryDoctrine;

class DeactivateTransactionController extends A_Controller
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
     *     path="/v1/transaction/deactivate/{id}",
     *     description="Deactivates a particular transaction based on its ID",
     *     @OA\Parameter(
     *          description="ID of a transaction to be deactivated",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              format="int64",
     *              type="integer"
     *          )
     *      ),
     * @OA\Response(
     *           response=200,
     *           description="Transaction has been deactivated successfully",
     *       ),
     * @OA\Response(
     *           response=400,
     *           description="bad request",
     *       ),
     *     @OA\Response(
     *                response=404,
     *            description="Transaction not found",
     *        ),
     *     @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *  )
     * @param Request $request
     * @param ResponseInterface $response
     * @param $args
     * @return ResponseInterface
     */
    public function deactivateAction(Request $request, ResponseInterface $response, $args): ResponseInterface
    {
        return parent::deactivateAction($request, $response, $args);
    }
}