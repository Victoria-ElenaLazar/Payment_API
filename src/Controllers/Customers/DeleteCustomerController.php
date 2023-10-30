<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Customers;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\CustomersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteCustomerController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(CustomersRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::Customers;
        $this->routeValue = Routes::Customers->value;
    }

    /**
     * @OA\Delete(
     *     path="/v1/customer/{id}",
     *     description="Deletes a particular customer based on its ID",
     *     @OA\Parameter(
     *         description="ID of a customer to be deleted",
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
     *         description="Customer has been deleted successfully"
     *     ),
     * @OA\Response(
     *            response=400,
     *            description="bad request",
     *        ),
     * @OA\Response(
     *                 response=404,
     *             description="Customer not found",
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