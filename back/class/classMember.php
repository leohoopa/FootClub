<?php
abstract class Member {
    protected string $firstname;
    protected string $lastname;
    protected string $picture;

    public function __construct(string $firstname, string $lastname, string $picture) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->picture = $picture;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }
    public function getLastname(): string {
        return $this->lastname;
    }
    public function getPicture(): string {
        return $this->picture;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }
    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }
    public function setPicture(string $picture): void {
        $this->picture = $picture;
    }
}

final class Player extends Member {
    private DateTime $birthdate;

    public function __construct(string $firstname, string $lastname, string $picture, DateTime $birthdate) {
        parent::__construct($firstname, $lastname, $picture);
        $this->birthdate = $birthdate;
    }

    public function getBirthdate(): DateTime {
        return $this->birthdate;
    }

    public function setBirthdate(DateTime $birthdate): void {
        $this->birthdate = $birthdate;
    }
}

final class StaffMember extends Member {
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