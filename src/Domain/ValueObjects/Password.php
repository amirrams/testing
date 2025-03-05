<?php

namespace Docfav\Domain\ValueObjects;

use Docfav\Domain\Exceptions\WeakPasswordException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Password
{

    #[ORM\Column(type: "string")]
    private string $hashedValue;

    public function __construct(string $password)
    {
        $this->ensureIsValid($password);
        $this->hashedValue = password_hash($password, PASSWORD_BCRYPT);
    }

    private function ensureIsValid(string $password): void
    {
        if (strlen($password) < 8)
        {
            throw new WeakPasswordException('The password must be at least 8 characters.');
        }
        if (!preg_match('/[A-Z]/', $password))
        {
            throw new WeakPasswordException('The password must contain at least one capital letter.');
        }
        if (!preg_match('/\d/', $password))
        {
            throw new WeakPasswordException('The password must contain at least one number.');
        }
        if (!preg_match('/[\W_]/', $password))
        {
            throw new WeakPasswordException('The password must contain at least one special character.');
        }
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedValue);
    }

}
