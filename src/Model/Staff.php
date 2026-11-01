<?php

namespace App\Model;

final class Staff extends Member
{
    private int $id;
    private string $role;

    public function __construct(string $firstname, string $lastname, string $picture, string $role, int $id = 0)
    {
        parent::__construct($firstname, $lastname, $picture);
        $this->role = $role;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
