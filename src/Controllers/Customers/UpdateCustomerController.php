<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Customers;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Repositories\CustomersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateCustomerController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(CustomersRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::Customers;
        $this->routeValue = Routes::Customers->value;
    }

    /**
     * @OA\Put(
     *     path="/v1/customer/{id}",
     *     description="Updates a particular customer",
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
     *          description="Customer has been updated successfully",
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
     * @param array $args
     * @return Response
     */

    public function updateAction(Request $request, Response $response, array $args): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        //@TODO: complete validation

        if (empty($requestBody) || !isset($requestBody['name']) || !isset($requestBody['address'])) {
            return new JsonResponse([
                'message' => 'Invalid request data: name is missing.',
            ], 400);
        }

        $name = filter_var($requestBody['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $address = filter_var($requestBody['address'], FILTER_SANITIZE_SPECIAL_CHARS);

        $customer = $this->repository->findById($args['id']);
        if (is_null($customer)) {
            $context = [
                'type' => '/errors/no_' . $this->routeValue . '_found',
                'title' => 'List of ' . $this->routeValue,
                'status' => 404,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue,
            ];
            $this->logger->critical('No ' . $this->routeValue . ' found', $context);
            return new JsonResponse($context, 404);
        }
        $this->model = $customer;
        $this->model->setName($name);
        $this->model->setAddress($address);

        return parent::updateAction($request, $response, $args);
    }
}