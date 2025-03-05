<?php

namespace Docfav\Domain\Events;

class UserRegisteredEvent
{

    private string $userId;

    private string $email;

    public function __construct(string $userId, string $email)
    {
        $this->userId = $userId;
        $this->email = $email;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

}
