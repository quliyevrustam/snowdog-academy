<?php

namespace Snowdog\Academy\Model;

class UserType
{
    const CHILD = 1;
    const ADULT = 2;

    public int $id;
    public string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
