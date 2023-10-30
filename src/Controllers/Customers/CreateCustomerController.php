<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Customers;

use PaymentApi\Routes\Routes;
use PaymentApi\Models\Customers;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Repositories\CustomersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateCustomerController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(CustomersRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::Customers;
        $this->routeValue = Routes::Customers->value;
    }

    /**
     * @OA\Post(
     *     path="/v1/customer",
     *     description="Creates a new customer",
     *     @OA\RequestBody(
     *          description="Input data format",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      description="The name of the customer",
     *                      type="string",
     *                  ),
     *              ),
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="address",
     *                      description="the address of the customer creating",
     *                      type="string",
     *                  ),
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Customer has been created successfully",
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
     * @param Response $response
     * @return Response
     */
    public function createAction(Request $request, Response $response): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody) || !isset($requestBody['name'])
            || !isset($requestBody['address'])) {

            $context = [
                'type' => "invalid_request",
                'title' => 'Invalid request: name/address is missing!',
                'status' => 400,
                'detail' => $this->model,
                'instance' => '/v1/' . $this->routeValue
            ];
            $this->logger->info('Invalid request data', $context);
            return new JsonResponse($context, 400);
        }

        $name = filter_var($requestBody['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $address = filter_var($requestBody['address'], FILTER_SANITIZE_SPECIAL_CHARS);

        $this->model = new Customers();
        $this->model->setName($name);
        $this->model->setAddress($address);
        $this->model->setIsActive(true);

        return parent::createAction($request, $response);
    }
}