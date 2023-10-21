<?php
declare(strict_types=1);

namespace PaymentApi\Controllers;

use Monolog\Logger;
use PaymentApi\Routes\Routes;
use PaymentApi\Models\A_Model;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\Exception\NotSupported;
use PaymentApi\Repositories\A_Repository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class A_Controller
{
    protected A_Repository $repository;
    protected Logger $logger;

    protected Routes $routeEnum;
    protected string $routeValue;

    protected A_Model $model;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(protected ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws NotSupported
     */
    protected function indexAction(Request $request, Response $response): ResponseInterface
    {
        $records = $this->repository->findAll();

        if (count($records) > 0) {
            return new JsonResponse([
                'type' => 'Success',
                'title' => 'List of ' . $this->routeValue,
                'status' => 200,
                'detail' => count($records),
                'instance' => '/v1/' . $this->routeValue
            ], 200);
        } else {
            $context = [
                'type' => '/errors/no_' . $this->routeValue . '_found',
                'title' => 'List of ' . $this->routeValue,
                'status' => 404,
                'detail' => count($records),
                'instance' => '/v1/' . $this->routeValue,
                'records' => $records
            ];
            $this->logger->critical('No ' . $this->routeValue . ' found', $context);
            return new JsonResponse($context, 404);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    protected function createAction(Request $request, Response $response): ResponseInterface
    {
        try {
            $this->repository->store($this->model);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
            return new JsonResponse([
                'message' => '/errors/internal_server_error_upon_create_' . $this->routeValue,
                'title' => 'Internal server error',
                'status' => 500,
                'detail' => $this->model,
                'instance' => '/v1/' . $this->routeValue,
            ], 500);
        }

        return new JsonResponse([
            'type' => 'Success',
            'message' => $this->routeEnum->toSingular() . ' created successfully',
            'status' => 200,
            'detail' => $this->model->getId(),
            'instance' => '/v1/' . $this->routeValue,
        ], 200);
    }

    protected function updateAction(Request $request, Response $response, array $args): ResponseInterface
    {
        try {
            $this->repository->update($this->model);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
            return new JsonResponse([
                'type' => '/errors/internal_server_error_upon_update_' . $this->routeValue,
                'title' => 'Internal server error',
                'status' => 500,
                'detail' => '',
                'instance' => '/v1/' . $this->routeValue . '/{id}'
            ], 500);
        }
        return new JsonResponse([
            'type' => 'Success',
            'title' => $this->routeEnum->toSingular() . ' has been updated',
            'status' => 200,
            'detail' => $args['id'],
            'instance' => '/v1/' . $this->routeValue . '/{id}'
        ], 200);
    }
    protected function deactivateAction(Request $request, Response $response, array $args):ResponseInterface
    {
        $records = $this->repository->findById($args['id']);
        if (is_null($records)) {
            $context = [
                'type' => '/errors/no_' . $this->routeValue . '_found_upon_deactivation',
                'title' => 'Deactivation of ' . $this->routeValue,
                'status' => 404,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue,
            ];
            $this->logger->critical('No ' . $this->routeValue . ' found', $context);
            return new JsonResponse($context, 404);
        }

        $records->setIsActive(false);
        try {

            $this->repository->update($records);
        }catch(\Exception $exception){
            $this->logger->critical($exception->getMessage());
            return new JsonResponse([
                'type' => '/errors/internal_server_error_upon_deactivate_' . $this->routeValue,
                'title' => 'Internal server error',
                'status' => 500,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue . '/deactivate/{id}'
            ], 500);

        }
        return new JsonResponse([
            'message' => $this->routeValue . ' has been deactivated successfully!',
            'status' => 200,
            'detail' => $args['id'],
            'instance' => '/v1/' . $this->routeValue . '/deactivate/{id}',
        ], 200);
    }

    protected function reactivateAction(Request $request, Response $response, array $args):ResponseInterface
    {
        $records = $this->repository->findById($args['id']);
        if (is_null($records)) {
            $context = [
                'type' => '/errors/no_' . $this->routeValue . '_found_upon_reactivation',
                'title' => 'Reactivation of ' . $this->routeValue,
                'status' => 404,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue,
            ];
            $this->logger->critical('No ' . $this->routeValue . ' found', $context);
            return new JsonResponse($context, 404);
        }

        $records->setIsActive(true);
        try {
            $this->repository->update($records);
        }catch(\Exception $exception){
            $this->logger->critical($exception->getMessage());
            return new JsonResponse([
                'type' => '/errors/internal_server_error_upon_reactivate_' . $this->routeValue,
                'title' => 'Internal server error',
                'status' => 500,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue . '/reactivate/{id}'
            ], 500);

        }

        return new JsonResponse([
            'type' => 'Success',
            'message' => $this->routeValue . ' has been reactivated successfully!',
            'status' => 200,
            'detail' => $args['id'],
            'instance' => '/v1/' . $this->routeValue . '/deactivate/{id}',
        ], 200);
    }
    protected function deleteAction(Request $request, Response $response, array $args):ResponseInterface
    {

        $records = $this->repository->findById($args['id']);
        if (is_null($records)) {
            $context = [
                'type' => '/errors/no_' . $this->routeValue . '_found_upon_deleting',
                'title' => 'Removing ' . $this->routeEnum->toSingular(),
                'status' => 404,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue . '/{id}',
            ];
            $this->logger->critical('No ' . $this->routeValue . ' found', $context);
            return new JsonResponse($context, 404);
        }
        try {

            $this->repository->remove($records);
        }catch (\Exception $exception){
            $this->logger->critical($exception->getMessage());
            return new JsonResponse([
                'type' => '/errors/internal_server_error_upon_remove_' . $this->routeValue,
                'title' => 'Internal server error',
                'status' => 500,
                'detail' => $args['id'],
                'instance' => '/v1/' . $this->routeValue .'/{id}'
            ], 500);
        }

        return new JsonResponse([
            'type' => 'Success',
            'message' => $this->routeEnum->toSingular() . ' has been deleted successfully!',
            'status' => 200,
            'detail' => $args['id'],
            'instance' => '/v1/' . $this->routeValue . '/{id}',
        ], 200);
    }
}