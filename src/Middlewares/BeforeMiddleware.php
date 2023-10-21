<?php
declare(strict_types=1);

namespace PaymentApi\Middlewares;

use DI\Container;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
require __DIR__ . '/../../vendor/autoload.php';


class BeforeMiddleware
{
    private Container $container;
    private array $validTokens;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->validTokens = [$_ENV['SECRET_KEY']];
    }

    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $token = $this->getTokenFromHeader($request);
        if (!$this->isValidToken($token)) {
            return new Response(401);
        }
        return $handler->handle($request);
    }

    private function getTokenFromHeader(Request $request): ?string
    {
        $headers = $request->getHeader('Authorization');
        $authHeader = reset($headers);

        if (str_starts_with($authHeader, 'Bearer ')) {
            return substr($authHeader, 7);
        }

        return null;
    }

    private function isValidToken($token): bool
    {
        return in_array($token, $this->validTokens);
    }
}
