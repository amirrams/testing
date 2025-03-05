<?php

namespace Docfav\Tests\Unit;

use Docfav\Domain\Entities\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testUserCreation(): void
    {
        $email = 'test@example.com';
        $name = 'James Bond';
        $password = 'Spain.2025';
        $user = User::create($name, $email, $password);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('James Bond', $user->getName());
        $this->assertTrue($user->getPassword()->verify('Spain.2025'));
    }

}
