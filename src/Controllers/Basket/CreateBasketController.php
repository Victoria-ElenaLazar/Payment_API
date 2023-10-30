<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Basket;

use PaymentApi\Models\Basket;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Doctrine\ORM\Exception\NotSupported;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;

class CreateBasketController extends A_Controller
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
     * @OA\Post(
     *     path="/v1/basket",
     *     description="Creates a basket",
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
     *          description="basket records has been created successfully",
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="bad request",
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="not found",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *   ),
     * )
     * @param \Slim\Psr7\Request $request
     * @param \Slim\Psr7\Response $response
     * @return ResponseInterface
     * @throws NotSupported
     */
    public function createAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody) || !isset($requestBody['product_name'])
            || !isset($requestBody['product_gtin'])
            || !isset($requestBody['quantity'])
            || !isset($requestBody['amount'])
            || !isset($requestBody['customer_id'])) {

            $context = [
                'type' => "invalid_request",
                'title' => 'Invalid request: data is missing!',
                'status' => 400,
                'detail' => $this->model,
                'instance' => '/v1/' . $this->routeValue
            ];
            $this->logger->info('Invalid request data', $context);
            return new JsonResponse($context, 400);
        }

        $productName = filter_var($requestBody['product_name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $productGTIN = filter_var($requestBody['product_gtin'], FILTER_SANITIZE_SPECIAL_CHARS);
        $quantity = filter_var($requestBody['quantity'], FILTER_SANITIZE_NUMBER_INT);
        $amount = filter_var($requestBody['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $customerId = filter_var($requestBody['customer_id'], FILTER_SANITIZE_NUMBER_INT);

        $customer = $this->customersRepository->findById((int)$customerId);
        if (is_null($customer)) {
            $context = [
                'type' => '/errors/no_customers_found_upon_basket_create',
                'title' => 'Customer not found',
                'status' => 404,
                'detail' => $customerId,
                'instance' => '/v1/basket'
            ];
            $this->logger->info('No customers found', $context);
            return new JsonResponse($context, 404);
        }

        $this->model = new Basket();
        $this->model->setProductName($productName);
        $this->model->setProductGTIN($productGTIN);
        $this->model->setQuantity((int)$quantity);
        $this->model->setAmount((float)$amount);
        $this->model->setCustomer($customer);

        return parent::createAction($request, $response);
    }
}