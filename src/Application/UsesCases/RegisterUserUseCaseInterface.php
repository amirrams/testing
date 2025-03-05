<?php

namespace Docfav\Application\UsesCases;

use Docfav\Application\DTOs\RegisterUserRequest;
use Docfav\Application\DTOs\UserResponseDTO;

interface RegisterUserUseCaseInterface
{

    public function deleteById(int $id): bool;

    public function findAll(): array;

    public function findByEmail(string $email): ?UserResponseDTO;

    public function findById(int $id): ?UserResponseDTO;

    public function save(RegisterUserRequest $request): ?UserResponseDTO;

}
