<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Customers;


use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\CustomersRepository;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomersController extends A_Controller
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(CustomersRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::Customers;
        $this->routeValue = Routes::Customers->value;
    }

    /**
     * @OA\Get(
     *     path="/v1/customer",
     *     description="Returns all customers",
     *     @OA\Response(
     *          response=200,
     *          description="Customer response",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *   )
     * )
     * @param Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotSupported
     */
    public function indexAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        return parent::indexAction($request, $response);
    }
}