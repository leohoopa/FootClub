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




