<?php

namespace Snowdog\Academy\Model;

class User
{
    public int $id;
    public string $login;
    public string $password;
    public bool $is_admin;
    public bool $is_active;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function getUserType(): int
    {
        return $this->user_type;
    }

    public function getUserTypeName(): string
    {
        return $this->user_type_name;
    }

    public function isChild(): bool
    {
        return $this->user_type == UserType::CHILD;
    }

    public function isAdult(): bool
    {
        return $this->user_type == UserType::ADULT;
    }
}
