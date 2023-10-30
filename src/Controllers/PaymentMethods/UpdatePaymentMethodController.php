<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\PaymentMethods;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use PaymentApi\Repositories\PaymentMethodsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdatePaymentMethodController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(PaymentMethodsRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::PaymentMethods;
        $this->routeValue = Routes::PaymentMethods->value;
    }

    /**
     * @OA\Put(
     *     path="/v1/payment-method/{id}",
     *     description="update a particular payment method based on its ID",
     *     @OA\Parameter(
     *          description="ID of a payment method to update",
     *          in="path",
     *          name="id",
     *          required=true,
     *          @OA\Schema(
     *              format="int64",
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *           description="Input data format",
     *           @OA\MediaType(
     *               mediaType="multipart/form-data",
     *               @OA\Schema(
     *                   type="object",
     *                   @OA\Property(
     *                       property="name",
     *                       description="name of paymnet method",
     *                       type="string",
     *                   ),
     *               ),
     *           ),
     *       ),
     * @OA\Response(
     *           response=200,
     *           description="payment method has been created successfully",
     *       ),
     * @OA\Response(
     *           response=400,
     *           description="bad request",
     *       ),
     *     @OA\Response(
     *                response=404,
     *            description="Payment method not found",
     *        ),
     *     @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *  )
     * @param \Slim\Psr7\Request $request
     * @param \Slim\Psr7\Response $response
     * @param array $args
     * @return ResponseInterface
     */
    public function updateAction(Request $request, Response $response, array $args): ResponseInterface
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

        $paymentMethodId = (int)$args['id'];

        $paymentMethods = $this->repository->findById($paymentMethodId);
        if (is_null($paymentMethods)) {
            $context = [
                'type' => '/errors/no_' . $this->routeValue . '_found',
                'title' => 'List of ' . $this->routeValue,
                'status' => 404,
                'detail' => (int)$args['id'],
                'instance' => '/v1/' . $this->routeValue . '/{id}',
            ];
            $this->logger->info('No ' . $this->routeValue . ' found', $context);
            return new JsonResponse($context, 404);
        }
        $this->model = $paymentMethods;
        $paymentMethods->setName($name);

        return parent::updateAction($request, $response, $args);
    }
}