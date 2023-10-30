<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\PaymentMethods;

use Slim\Psr7\Response;
use PaymentApi\Routes\Routes;
use PaymentApi\Models\PaymentMethods;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Repositories\PaymentMethodsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreatePaymentMethodController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(PaymentMethodsRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::PaymentMethods;
        $this->routeValue = Routes::PaymentMethods->value;
    }

    /**
     * @OA\Post(
     *     path="/v1/payment-method",
     *     description="Creates a new payment method",
     *     @OA\RequestBody(
     *          description="Input data format",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      description="name of a new payment method",
     *                      type="string",
     *                  ),
     *              ),
     *          ),
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="payment method has been created successfully",
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="bad request",
     *      ),
     *     @OA\Response(
     *          response=409,
     *          description="name already exists",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *   ),
     * )
     * @param \Slim\Psr7\Request $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function createAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        if (empty($requestBody) || !isset($requestBody['name'])) {
            $context = [
                'type' => "invalid_request",
                'title' => 'Invalid request data: name is missing.',
                'status' => 400,
                'detail' => $this->model,
                'instance' => '/v1/' . $this->routeValue
            ];
            $this->logger->info('Invalid request data', $context);
            return new JsonResponse($context, 400);
        }

        $name = filter_var($requestBody['name'], FILTER_SANITIZE_SPECIAL_CHARS);

        $existingPaymentMethod = $this->repository->findPaymentMethodByName($name);
        if ($existingPaymentMethod !== null) {
            $context = [
                'type' => "name_already_exists",
                'title' => 'A payment method with the same name already exists.',
                'status' => 409,
                'detail' => '',
                'instance' => '/v1/' . $this->routeValue
            ];
            $this->logger->info('The name already exists', $context);
            return new JsonResponse($context, 409);
        }

        $this->model = new PaymentMethods();
        $this->model->setName($name);
        $this->model->setIsActive(true);

        return parent::createAction($request, $response);
    }
}