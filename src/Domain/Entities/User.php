<?php

namespace Docfav\Domain\Entities;

use DateTimeImmutable;
use Docfav\Domain\ValueObjects\Email;
use Docfav\Domain\ValueObjects\Name;
use Docfav\Domain\ValueObjects\Password;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    private int $id;

    #[Embedded(class: Name::class)]
    private Name $name;

    #[Embedded(class: Email::class)]
    private Email $email;

    #[Embedded(class: Password::class)]
    private Password $password;

    #[ORM\Column(type: "datetime_immutable")]
    private DateTimeImmutable $createdAt;

    private function __construct(string $name, string $email, string $password, DateTimeImmutable $createdAt)
    {
        $this->name = new Name($name);
        $this->email = new Email($email);
        $this->password = new Password($password);
        $this->createdAt = $createdAt;
    }

    public static function create(string $name, string $email, string $password): self
    {
        return new self($name, $email, $password, new DateTimeImmutable());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name->getValue();
    }

    public function getEmail(): string
    {
        return $this->email->getValue();
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

}
