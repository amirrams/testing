<?php

namespace Docfav\Infrastructure\Persistence\Doctrine;

use Docfav\Domain\Entities\User;
use Docfav\Domain\Repositories\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function delete(int $id): void
    {
        $user = $this->findById($id);
        if ($user !== null)
        {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function findByEmail(string $email): ?User
    {
        $result = $this->entityManager->getRepository(User::class)->findOneBy(['email.value' => $email]);
        if ($result instanceof User) {
            return $result;
        }
        return null;
    }

    public function findById(int $id): ?User
    {
        $result = $this->entityManager->getRepository(User::class)->find($id);
        if ($result instanceof User) {
            return $result;
        }
        return null;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
