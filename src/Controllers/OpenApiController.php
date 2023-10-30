<?php
declare(strict_types=1);

namespace PaymentApi\Controllers;
error_reporting(1);


use OpenApi\Generator;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface as Request;

require_once __DIR__ . '/../../vendor/autoload.php';
class OpenAPIController
{
    /**
     * @throws \JsonException
     */
    public function documentationAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        $openapi = Generator::scan([__DIR__ . '/../../src']);
        return new JsonResponse(json_decode($openapi->toJson(), true, 512, JSON_THROW_ON_ERROR));
    }
}