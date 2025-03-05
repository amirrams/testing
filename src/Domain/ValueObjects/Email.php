<?php

namespace Docfav\Domain\ValueObjects;

use Docfav\Domain\Exceptions\InvalidEmailException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Email
{

    #[ORM\Column(type: "string")]
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidEmailException("Invalid email format.");
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
