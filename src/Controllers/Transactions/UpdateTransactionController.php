<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Transactions;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Doctrine\ORM\Exception\NotSupported;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\TransactionRepository;
use PaymentApi\Repositories\BasketRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PaymentApi\Repositories\PaymentMethodsRepositoryDoctrine;

class UpdateTransactionController extends A_Controller
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
     * @OA\Put(
     *     path="/v1/transaction",
     *     description="Updates a particular transaction based on its ID",
     *     @OA\RequestBody(
     *          description="Input data format",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="payment_method_name",
     *                      description="The name of the payment method used",
     *                      type="string",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="customer_name",
     *                      description="The name of the customer who had the transaction",
     *                      type="string",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="basket_id",
     *                      description="The id of the basket customer has",
     *                      type="integer",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="transaction_date",
     *                      description="The date when transaction was/will be created",
     *                      type="float",
     *                  ),
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Transaction has been updated successfully",
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="bad request",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *   ),
     * )
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws NotSupported
     */
    public function updateAction(Request $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (
            empty($requestBody) ||
            !isset($requestBody['payment_method_name']) ||
            !isset($requestBody['customer_name']) ||
            !isset($requestBody['basket_id'])
        ) {
            return new JsonResponse([
                'message' => 'Invalid request data: payment method name, customer name, or basket id is missing.',
            ], 400);
        }

        $paymentMethodName = filter_var($requestBody['payment_method_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $customerName = filter_var($requestBody['customer_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $basketId = filter_var($requestBody['basket_id'], FILTER_SANITIZE_NUMBER_INT);
        $sent = filter_var($requestBody['sent'], FILTER_SANITIZE_NUMBER_INT);
        $amount = filter_var($requestBody['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $transactionDate = filter_var($requestBody['transaction_date'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $paymentMethodId = $this->paymentMethodsRepository->findPaymentMethodIdByName($paymentMethodName);
        $customerId = $this->customerRepository->findCustomerIdByName($customerName);

        if ($paymentMethodId <= 0 || $customerId <= 0 || $basketId <= 0) {
            return new JsonResponse([
                'message' => 'Invalid request data: payment method name or customer name not found or basket_id is not a positive integer.',
            ], 400);
        }

        $paymentMethod = $this->paymentMethodsRepository->findById((int)$paymentMethodId);
        $customer = $this->customerRepository->findById((int)$customerId);
        $basket = $this->basketRepository->findById((int)$basketId);

        if (is_null($paymentMethod) || is_null($customer) || is_null($basket)) {
            $context = [
                'type' => '/errors/no_payment_method_/_customer_/_basket_found_upon_transaction_create',
                'title' => 'Payment Method/Customer/Basket not found',
                'status' => 404,
                'detail' => $paymentMethodId, $customerId, $basketId,
                'instance' => '/v1/basket'
            ];
            $this->logger->info('No payment method/Customer/Basket found', $context);
            return new JsonResponse($context, 404);
        }
        $transactionId = $args['id'];
        $transaction = $this->repository->findById((int)$transactionId);

        if ($transaction === null) {
            $context = [
                'type' => '/errors/transaction_not_found',
                'title' => 'Transaction not found',
                'status' => 404,
                'detail' => (int)$transactionId,
                'instance' => '/v1/transaction',
            ];
            $this->logger->info('Transaction not found', $context);
            return new JsonResponse($context, 404);
        }

        $this->model = $transaction;
        $transaction->setPaymentMethodId((int)$paymentMethodId);
        $transaction->setPaymentMethodName($paymentMethodName);
        $transaction->setCustomerId((int)$customerId);
        $transaction->setCustomerName($customerName);
        $transaction->setBasketId((int)$basketId);
        $transaction->setSent((bool)$sent);
        $transaction->setAmount((float)$amount);
        $transaction->setTransactionDate($transactionDate);
        $transaction->setPaymentMethod($paymentMethod);
        $transaction->setCustomer($customer);
        $transaction->setBasket($basket);

        return parent::updateAction($request, $response, $args);
    }
}