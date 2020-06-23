<?php declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class User implements UserInterface, JWTUserInterface, EquatableInterface
{
    private int $id;

    private array $roles = [];

    public function __construct(int $id, array $payload = [])
    {
        $this->id = $id;
        if (!empty($payload['roles'])) {
            $this->roles = $payload['roles'];
        }
    }

    public static function createFromPayload($username, array $payload): User
    {
        return new self($payload['username'], $payload);
    }

    public function isEqualTo(UserInterface $user): bool
    {
        $rolesDiff = array_diff(
            $this->getRoles(),
            $user->getRoles()
            );
        
        return $this->getUsername() === $user->getUsername() && 0 === count($rolesDiff);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getUsername(): int
    {
        return $this->id;
    }

    public function eraseCredentials(): self
    {
        return $this;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
