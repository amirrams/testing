<?php

namespace Docfav\Tests\Unit;

use Docfav\Domain\Exceptions\InvalidEmailException;
use Docfav\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{

    public function testEmailIsValid(): void
    {
        $email = new Email("test@example.com");
        $this->assertEquals("test@example.com", $email->getValue());
    }

    public function testEmailWithoutAtSymbolThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new Email("testexample.com");
    }

    public function testEmailWithoutDomainExtensionThrowsException(): void
    {
        $this->expectException(InvalidEmailException::class);
        new Email("test@examplecom");
    }

}
