<?php

namespace Docfav\Tests\Unit;

use Docfav\Domain\Exceptions\WeakPasswordException;
use Docfav\Domain\ValueObjects\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{

    public function testValidPassword(): void
    {
        $password = new Password('Spain.1234');
        $this->assertTrue($password->verify('Spain.1234'));
        $this->assertFalse($password->verify('Spain.5678'));
    }

    public function testPasswordWithoutUppercaseThrowsException()
    {
        $this->expectException(WeakPasswordException::class);
        new Password('spain.1234');
    }

    public function testPasswordWithoutNumberThrowsException()
    {
        $this->expectException(WeakPasswordException::class);
        new Password('Spain.spain');
    }

    public function testPasswordWithoutSpecialCharacterThrowsException()
    {
        $this->expectException(WeakPasswordException::class);
        new Password('Spain12345');
    }

}
