<?php

namespace Docfav\Tests\Unit;

use Docfav\Application\DTOs\RegisterUserRequest;
use Docfav\Application\UsesCases\RegisterUserUseCase;
use Docfav\Domain\Events\UserRegisteredEvent;
use Docfav\Domain\Exceptions\UserAlreadyExistsException;
use Docfav\Infrastructure\Events\InMemoryEventDispatcher;
use Docfav\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class RegisterUserUseCaseTest extends TestCase
{

    private EntityManager $entityManager;

    private InMemoryEventDispatcher $eventDispatcher;

    private RegisterUserUseCase $useCase;

    /**
     * Crear el esquema de la base de datos.
     */
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

        $userRepository = new DoctrineUserRepository($this->entityManager);
        $this->eventDispatcher = new InMemoryEventDispatcher();
        $this->useCase = new RegisterUserUseCase($userRepository, $this->eventDispatcher);
    }

    /**
     * Limpiar la base de datos después de cada prueba.
     */
    protected function tearDown(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testDelete(): void
    {
        $this->useCase->save(new RegisterUserRequest('John Doe', 'test@example.com', 'Spain.2025'));
        $result = $this->useCase->deleteById(1);
        $this->assertTrue($result);
    }

    public function testFindAll(): void
    {
        $this->useCase->save(new RegisterUserRequest('John Doe', 'test@example.com', 'Spain.2025'));
        $users = $this->useCase->findAll();
        $this->assertNotNull($users);
        $this->assertNotEmpty($users);
        $this->assertCount(1, $users);
    }

    public function testFindById(): void
    {
        $this->useCase->save(new RegisterUserRequest('John Doe', 'test@example.com', 'Spain.2025'));
        $user = $this->useCase->findById(1);
        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testFindByEmail(): void
    {
        $this->useCase->save(new RegisterUserRequest('John Doe', 'test@example.com', 'Spain.2025'));
        $user = $this->useCase->findByEmail('test@example.com');
        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testSave(): void
    {
        $request = new RegisterUserRequest('James Bond', 'test@example.com', 'Mexico.2025');

        $user = $this->useCase->save($request);

        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->getEmail());

        $events = $this->eventDispatcher->getDispatchedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegisteredEvent::class, $events[0]);
    }

    public function testSaveEmailAlreadyExistsThrowsException(): void
    {
        $request = new RegisterUserRequest('John Doe', 'test@example.com', 'Mexico.2025');
        $this->useCase->save($request);
        $request = new RegisterUserRequest('John Connor', 'test@example.com', 'Mexico.1234');
        $this->expectException(UserAlreadyExistsException::class);
        $this->useCase->save($request);
    }

}
