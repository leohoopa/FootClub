<?php

require_once __DIR__ . '/Member.php';

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
