<?php

namespace Docfav\Application\UsesCases;

use Docfav\Application\DTOs\RegisterUserRequest;
use Docfav\Application\DTOs\UserResponseDTO;
use Docfav\Domain\Entities\User;
use Docfav\Domain\Exceptions\UserAlreadyExistsException;
use Docfav\Domain\Repositories\UserRepositoryInterface;
use Docfav\Domain\Events\UserRegisteredEvent;
use Docfav\Domain\EventDispatcherInterface;
use DomainException;

class RegisterUserUseCase implements RegisterUserUseCaseInterface
{

    private UserRepositoryInterface $userRepository;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserRepositoryInterface $userRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function deleteById(int $id): bool
    {
        $user = $this->userRepository->findById($id);
        if ($user === null)
        {
            throw new DomainException("User not found.");
        }
        $this->userRepository->delete($id);
        return true;
    }

    public function findAll(): array
    {
        $users = $this->userRepository->findAll();
        return array_map(function ($user) {
            return new UserResponseDTO($user);
        }, $users);
    }

    public function findByEmail(string $email): ?UserResponseDTO
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null)
        {
            throw new DomainException("User not found.");
        }
        return new UserResponseDTO($user);
    }

    public function findById(int $id): ?UserResponseDTO
    {
        $user = $this->userRepository->findById($id);
        if ($user === null)
        {
            throw new DomainException("User not found.");
        }
        return new UserResponseDTO($user);
    }

    public function save(RegisterUserRequest $request): ?UserResponseDTO
    {
        $existingUser = $this->userRepository->findByEmail($request->getEmail());
        if ($existingUser !== null)
        {
            throw new UserAlreadyExistsException("The email is already in use.");
        }

        $entity = User::create(
            $request->getName(),
            $request->getEmail(),
            $request->getPassword()
        );
        $this->userRepository->save($entity);

        $user = $this->userRepository->findByEmail($request->getEmail());
        if ($user !== null)
        {
            $event = new UserRegisteredEvent($user->getId(), $user->getEmail());
            $this->eventDispatcher->dispatch($event);
            return new UserResponseDTO($user);
        }
        return null;
    }

}
