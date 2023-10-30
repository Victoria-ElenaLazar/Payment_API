<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\PaymentMethods;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use Psr\Http\Message\ResponseInterface as Response;
use PaymentApi\Repositories\PaymentMethodsRepository;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeactivatePaymentMethodController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(PaymentMethodsRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::PaymentMethods;
        $this->routeValue = Routes::PaymentMethods->value;
    }

    /**
     * @OA\Get(
     *     path="/v1/payment-method/deactivate/{id}",
     *     description="Deactivates a particular payment method based on its ID",
     *     @OA\Parameter(
     *          description="ID of a payment method to be deactivated",
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
     *           description="payment method has been deactivated successfully",
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

    public function deactivateAction(Request $request, Response $response, array $args): ResponseInterface
    {
        return parent::deactivateAction($request, $response, $args);
    }
}