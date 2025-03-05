<?php

namespace Docfav\Tests\Integration;

use Docfav\Domain\Entities\User;
use Docfav\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [PROJECT_ROOT . '/src'],
            isDevMode: true
        );

        $connection = DriverManager::getConnection([
            'dbname' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD,
            'host' => DB_HOST,
            'driver' => 'pdo_mysql',
        ], $config);
        $this->entityManager = new EntityManager($connection, $config);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    protected function tearDown(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testSaveAndRetrieveUser(): void
    {
        $repository = new DoctrineUserRepository($this->entityManager);

        $email = 'test@example.com';
        $name = 'James Bond';
        $password = 'Mexico.2025';

        $user = User::create($name, $email, $password);
        $repository->save($user);

        $retrievedUser = $repository->findByEmail('test@example.com');
        $this->assertNotNull($retrievedUser);
        $this->assertEquals('test@example.com', $retrievedUser->getEmail());
    }

}
