<?php

namespace Docfav\Domain\ValueObjects;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Embeddable]
class Name
{

    #[ORM\Column(type: "string")]
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 3)
        {
            throw new InvalidArgumentException("Name must be at least 3 characters long.");
        }
        if (!preg_match('/^[a-zA-Z\s]+$/', $value))
        {
            throw new InvalidArgumentException("Name can only contain letters and spaces.");
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
