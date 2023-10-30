<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Basket;

use Doctrine\ORM\Exception\NotSupported;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\BasketRepository;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Repositories\CustomersRepositoryDoctrine;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class BasketController extends A_Controller
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
     *     path="/v1/basket",
     *     description="Returns all baskets",
     *     @OA\Response(
     *          response=200,
     *          description="basket response",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *   )
     * )
     * @return Response
     * @throws NotSupported
     */
    public function indexAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        return parent::indexAction($request, $response);
    }

    /**
     * @OA\Get(
     *     path="/v1/basket/{id}",
     *     description="Returns a particular basket record",
     *     @OA\Response(
     *          response=200,
     *          description="Basket response",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *   )
     * )
     * @return Response
     */
    public function getAction(Request $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $basketId = $args['id'];
        $basket = $this->repository->findById((int)$basketId);

        if ($basket === null) {
            $context = [
                'type' => '/errors/basket_not_found',
                'title' => 'Basket not found',
                'status' => 404,
                'detail' => (int)$basketId,
                'instance' => '/v1/' . $this->routeValue . '/{id}',
            ];
            $this->logger->info('Basket not found', $context);
            return new JsonResponse($context, 404);
        }

        return new JsonResponse([
            'type' => 'Success',
            'title' => 'Basket Details',
            'status' => 200,
            'detail' => [
                'product name' => $basket->getProductName(),
                'product GTIN' => $basket->getProductGTIN(),
                'product quantity' => $basket->getQuantity(),
                'product amount' => $basket->getAmount(),
                'customer name' => $basket->getCustomer()->getName(),
                'customer address' => $basket->getCustomer()->getAddress(),
            ],
            'instance' => '/v1/' . $this->routeValue,
        ], 200);
    }
}