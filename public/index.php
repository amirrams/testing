<?php

require_once dirname(__DIR__) . "/bootstrap.php";

use Docfav\Application\Handlers\SendWelcomeEmailHandler;
use Docfav\Application\UsesCases\RegisterUserUseCase;
use Docfav\Domain\Events\UserRegisteredEvent;
use Docfav\Infrastructure\Events\InMemoryEventDispatcher;
use Docfav\Infrastructure\Http\RegisterUserController;
use Docfav\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [PROJECT_ROOT . '/src'],
    isDevMode: true,
);

$connection = DriverManager::getConnection([
    'dbname' => DB_NAME,
    'user' => DB_USER,
    'password' => DB_PASSWORD,
    'host' => DB_HOST,
    'driver' => 'pdo_mysql',
], $config);

$eventDispatcher = new InMemoryEventDispatcher();

$eventDispatcher->register(UserRegisteredEvent::class, new SendWelcomeEmailHandler());

$entityManager = new EntityManager($connection, $config);

$userRepository = new DoctrineUserRepository($entityManager);

$useCase = new RegisterUserUseCase($userRepository, $eventDispatcher);

$controller = new RegisterUserController($useCase, $userRepository);

$controller->handleRequest();