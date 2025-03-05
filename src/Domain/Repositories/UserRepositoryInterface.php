<?php

namespace Docfav\Domain\Repositories;

use Docfav\Domain\Entities\User;

interface UserRepositoryInterface
{

    public function save(User $user): void;

    public function findAll();

    public function findByEmail(string $id): ?User;

    public function findById(int $id): ?User;

    public function delete(int $id): void;

}
