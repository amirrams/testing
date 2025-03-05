<?php

namespace Docfav\Infrastructure\Http;

use Docfav\Application\DTOs\RegisterUserRequest;
use Docfav\Application\UsesCases\RegisterUserUseCaseInterface;
use Docfav\Domain\Repositories\UserRepositoryInterface;
use DomainException;
use Exception;

class RegisterUserController
{

    private RegisterUserUseCaseInterface $registerUserUseCase;

    public function __construct(RegisterUserUseCaseInterface $registerUserUseCase, UserRepositoryInterface $userRepository)
    {
        $this->registerUserUseCase = $registerUserUseCase;
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        try
        {
            if ($method === 'DELETE' && $uri === '/delete')
            {
                $this->handleDeleteUser();
            } elseif ($method === 'GET' && $uri === '/ping')
            {
                $this->handlePing();
            } elseif ($method === 'POST' && $uri === '/register')
            {
                $this->handlePostRegister();
            } elseif ($method === 'GET' && $uri === '/user')
            {
                $this->handleGetUser();
            } elseif ($method === 'GET' && $uri === '/users')
            {
                $this->handleGetUsers();
            } else
            {
                $this->sendJsonResponse(['error' => 'Route not found'], 404);
            }
        } catch (DomainException $e)
        {
            $this->sendJsonResponse(['error' => $e->getMessage()], 409);
        } catch (Exception $e)
        {
            $this->sendJsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function handlePostRegister(): void
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!isset($data['name'], $data['email'], $data['password']))
        {
            $this->sendJsonResponse(['error' => 'Invalid input data'], 400);
            return;
        }

        $registerRequest = new RegisterUserRequest(
            $data['name'],
            $data['email'],
            $data['password']
        );

        $userResponse = $this->registerUserUseCase->save($registerRequest);
        if ($userResponse === null)
        {
            $this->sendJsonResponse(['error' => 'An unexpected error occurred'], 500);
            return;
        }
        $this->sendJsonResponse($userResponse->toArray(), 201);
    }

    private function handleGetUser()
    {
        if (!empty($_GET['id']))
        {
            $user = $this->registerUserUseCase->findById($_GET['id']);
        } elseif (!empty($_GET['email']))
        {
            $user = $this->registerUserUseCase->findByEmail($_GET['email']);
        } else
        {
            $this->sendJsonResponse(['error' => 'Invalid input data'], 400);
            return;
        }
        $this->sendJsonResponse(['user' => $user], 200);
    }

    private function handleGetUsers(): void
    {
        $users = $this->registerUserUseCase->findAll();
        $this->sendJsonResponse(['users' => $users], 200);
    }

    private function sendJsonResponse(array $data, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function handlePing()
    {
        $this->sendJsonResponse(['message' => 'Server OK.'], 200);
    }

    private function handleDeleteUser()
    {
        if (!empty($_GET['id']))
        {
            $this->registerUserUseCase->deleteById($_GET['id']);
            $this->sendJsonResponse(['message' => "User {$_GET['id']} deleted."], 200);
        } else
        {
            $this->sendJsonResponse(['error' => 'Invalid input data'], 400);
        }
    }

}
