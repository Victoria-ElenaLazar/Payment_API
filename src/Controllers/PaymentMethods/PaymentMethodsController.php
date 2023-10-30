<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\PaymentMethods;


use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\PaymentMethodsRepository;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class PaymentMethodsController extends A_Controller
{
    /**
     * @param ContainerInterface $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(PaymentMethodsRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::PaymentMethods;
        $this->routeValue = Routes::PaymentMethods->value;
    }

    /**
     * @OA\Get(
     *     path="/v1/payment-method",
     *     description="Returns all the payment methods",
     *     @OA\Response(
     *          response=200,
     *          description="payment methods response",
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *      ),
     *   )
     * )
     * @return \Laminas\Diactoros\Response
     * @throws NotSupported
     */

    public function indexAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        return parent::indexAction($request, $response);
    }
}
