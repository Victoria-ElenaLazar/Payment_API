<?php
declare(strict_types=1);

namespace PaymentApi\Controllers\Users;

use Firebase\JWT\JWT;
use PaymentApi\Exceptions\ValidationException;
use PaymentApi\Models\Users;
use PaymentApi\Routes\Routes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use PaymentApi\Controllers\A_Controller;
use PaymentApi\Repositories\UsersRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsersController extends A_Controller
{
    public function __construct(ContainerInterface $container)
    {
        $this->repository = $container->get(UsersRepository::class);

        parent::__construct($container);
        $this->routeEnum = Routes::User;
        $this->routeValue = Routes::User->value;
    }

    /**@OA\Post(
     *     path="/v1/user/registration",
     *     description="Register a new user",
     *     @OA\RequestBody(
     *     description="Input data format",
     *     @OA\MediaType(
     *     mediaType="multipart/form-data",
     *     @OA\Schema(
     *     type="object",
     *     @OA\Property(
     *     property="name",
     *     description="User's first name and last name",
     *     type="string",
     *     ),
     *     ),
     *     @OA\Schema(
     *     type="object",
     *     @OA\Property(
     *     property="email",
     *     description="User's email",
     *     type="string",
     *     ),
     *     ),
     *     @OA\Schema (
     *     type="object",
     *     @OA\Property (
     *     property="birthday",
     *     type="string,
     *     ),
     *     ),
     *     @OA\Schema (
     *     type="object",
     *     @OA\Property (
     *     property="password",
     *     type="string",
     *     ),
     *     ),
     *     ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="User {User's name} has been created successfully",
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="bad request",
     *      ),
     *      @OA\Response(
     *            response=500,
     *            description="Internal server error",
     *        ),
     *   ),
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws ValidationException
     */
    public function registration(Request $request, Response $response, $args): ResponseInterface
    {
        $requestBody = json_decode($request->getBody()->getContents(), true);

        $validationErrors = [];

        if (empty($requestBody)) {
            $validationErrors['message'] = 'Request body is empty';
        } else {
            $name = filter_var($requestBody['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $address = filter_var($requestBody['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var($requestBody['email'], FILTER_VALIDATE_EMAIL);
            $birthDate = filter_var($requestBody['birthday'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_var($requestBody['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty($name)) {
                $validationErrors['name'] = 'Name is required and should not be empty';
            }

            if (empty($address)) {
                $validationErrors['address'] = 'Address is required and should not be empty';
            }

            if (empty($email)) {
                $validationErrors['email'] = 'Email is required and should be a valid email address';
            }

            if (empty($birthDate)) {
                $validationErrors['birthday'] = 'Birthday is required and should be a valid email address';
            }

            if (empty($password)) {
                $validationErrors['password'] = 'Password is required';
            }
        }

        if (!empty($validationErrors)) {
            throw new ValidationException('Validation failed', 400, null, $validationErrors);
        }

        $this->model = new Users();
        $this->model->setName($name);
        $this->model->setAddress($address);
        $this->model->setEmail($email);
        $this->model->setBirthDate($birthDate);
        $this->model->setPassword($password);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->model->setPassword($hashedPassword);

        $payload = [
            'email' => $email,
            'password' => $password
        ];

        $jwt = $this->generateJWT($email, $password);
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
            'message' => 'User ' . $name . ' registered successfully.',
            'user_id' => $this->model->getId(),
            'jwt' => $jwt,
        ], 201);
    }

    private function generateJWT(string $email, string $password): string
    {
        $algorithm = 'HS256';

        $payload = [
            'email' => $email,
            'password' => $password,
        ];

        return JWT::encode($payload, '', $algorithm);
    }

}