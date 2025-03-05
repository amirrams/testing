<?php

namespace Docfav\Tests\Unit;

use Docfav\Domain\ValueObjects\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{

    public function testValidName(): void
    {
        $name = new Name("James Bond");
        $this->assertEquals("James Bond", $name->getValue());
    }

    public function testNameTooShortThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Name must be at least 3 characters long.");
        new Name("Ja");
    }

    public function testNameWithInvalidCharactersThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Name can only contain letters and spaces.");
        new Name("James12345");
    }

    public function testNameWithSpecialCharactersThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Name can only contain letters and spaces.");
        new Name("James@Bond");
    }

}
