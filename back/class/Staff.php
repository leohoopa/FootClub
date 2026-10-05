<?php

final class Staff extends Member {
    private string $role;

    public function __construct(string $firstname, string $lastname, string $picture, string $role) {
        parent::__construct($firstname, $lastname, $picture);
        $this->role = $role;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }
}
