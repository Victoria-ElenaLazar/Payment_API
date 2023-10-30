<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Basket;

use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;

class DeactivateBasketController extends A_Controller
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
     * @OA\Get(
     *     path="/v1/basket/deactivate/{id}",
     *     description="Deactivates a particular basket based on its ID",
     *     @OA\Parameter(
     *          description="ID of a basket to be deactivated",
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
     *           description="Basket has been deactivated successfully",
     *       ),
     * @OA\Response(
     *           response=400,
     *           description="bad request",
     *       ),
     *     @OA\Response(
     *                response=404,
     *            description="Basket not found",
     *        ),
     *     @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *  )
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function deactivateAction(Request $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return parent::deactivateAction($request, $response, $args);
    }
}