<?php
declare(strict_types=1);

namespace PaymentApi\Middlewares;

use PaymentApi\Exceptions\DBException;
use Slim\App;
use Exception;
use Throwable;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\Exception\ORMException;
use Slim\Exception\HttpNotFoundException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CustomErrorHandler
{
    private Logger $logger;

    /**
     * @param App $app
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(private readonly App $app)
    {
        $this->logger = $this->app->getContainer()->get(Logger::class);
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @param LoggerInterface|null $logger
     * @return ResponseInterface
     * @throws DBException
     */
    public function __invoke(
        Request          $request,
        Throwable        $exception,
        bool             $displayErrorDetails,
        bool             $logErrors,
        bool             $logErrorDetails,
        ?LoggerInterface $logger = null
    ): ResponseInterface
    {
        $statusCode = 500;
        if ($exception instanceof ORMException
            || $exception instanceof HttpNotFoundException
            || $exception instanceof \PDOException) {
            $this->logger->critical(new DBException('Database Exception: An error occurred while processing your command', 500));
            $statusCode = 500;
        } else if ($exception instanceof Exception) {
            $this->logger->info($exception->getMessage());
            $statusCode = $exception->getCode();
        }

        $payload = [
            'message' => $exception->getMessage()
        ];

        if ($displayErrorDetails) {
            $payload['details'] = $exception->getMessage();
            $payload['trace'] = $exception->getTrace();
        }

        $response = $this->app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response->withStatus($statusCode);
    }
}
