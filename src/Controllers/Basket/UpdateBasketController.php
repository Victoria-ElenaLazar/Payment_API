<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Basket;

use Slim\Psr7\Response;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Doctrine\ORM\Exception\NotSupported;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateBasketController extends A_Controller
{
    private CustomersRepositoryDoctrine $customersRepository;

    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(BasketRepository::class);
        $this->customersRepository = $container->get(CustomersRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::Basket;
        $this->routeValue = Routes::Basket->value;
    }

    /**
     * @OA\Put(
     *     path="/v1/basket",
     *     description="Updates a new basket",
     *     @OA\RequestBody(
     *          description="Input data format",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="product_name",
     *                      description="Name of the product",
     *                      type="string",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="product_gtin",
     *                      description="GTIN of the product",
     *                      type="string",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="quantity",
     *                      description="Quantity of product",
     *                      type="integer",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="amount",
     *                      description="Basket product amount",
     *                      type="float",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="customer_id",
     *                      description="ID of customer",
     *                      type="integer",
     *                  ),
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Basket records has been updated successfully",
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
     * @param \Slim\Psr7\Request $request
     * @param Response $response
     * @param array $args
     * @return ResponseInterface
     * @throws NotSupported
     */
    public function updateAction(Request $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        $productName = filter_var($requestBody['product_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $productGTIN = filter_var($requestBody['product_gtin'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $quantity = filter_var($requestBody['quantity'], FILTER_SANITIZE_NUMBER_INT);
        $amount = filter_var($requestBody['amount'], FILTER_VALIDATE_FLOAT | FILTER_FLAG_ALLOW_THOUSAND);
        $customerId = filter_var($requestBody['customer_id'], FILTER_SANITIZE_NUMBER_INT);

        $customer = $this->customersRepository->findById((int)$customerId);
        if ($customer === null) {
            $context = [
                'type' => '/errors/customer_not_found',
                'title' => 'Customer not found',
                'status' => 404,
                'detail' => $customerId,
                'instance' => '/v1/basket',
            ];
            $this->logger->info('Customer not found', $context);
            return new JsonResponse($context, 404);
        }

        $basketId = $args['id'];
        $basket = $this->repository->findById((int)$basketId);

        if ($basket === null) {
            $context = [
                'type' => '/errors/basket_not_found',
                'title' => 'Basket not found',
                'status' => 404,
                'detail' => (int)$basketId,
                'instance' => '/v1/basket',
            ];
            $this->logger->info('Basket not found', $context);
            return new JsonResponse($context, 404);
        }

        $this->model = $basket;
        $basket->setProductName($productName);
        $basket->setProductGTIN($productGTIN);
        $basket->setQuantity((int)$quantity);
        $basket->setAmount((float)$amount);
        $basket->setCustomer($customer);

        return parent::updateAction($request, $response, $args);
    }
}