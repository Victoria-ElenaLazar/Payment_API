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

class DeletePaymentMethodController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(PaymentMethodsRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::PaymentMethods;
        $this->routeValue = Routes::PaymentMethods->value;
    }

    /**
     * @OA\Delete(
     *     path="/v1/payment-method/{id}",
     *     description="deletes a particular payment method based on its ID",
     *     @OA\Parameter(
     *         description="ID of a payment method to be deleted",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             format="int64",
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment method has been deleted successfully"
     *     ),
     * @OA\Response(
     *            response=400,
     *            description="bad request",
     *        ),
     * @OA\Response(
     *                 response=404,
     *             description="Payment method not found",
     *         ),
     * @OA\Response(
     *             response=500,
     *             description="Internal server error",
     *         ),
     *   )
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */

    public function deleteAction(Request $request, Response $response, $args): ResponseInterface
    {
        return parent::deleteAction($request, $response, $args);
    }
}