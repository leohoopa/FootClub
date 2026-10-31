<?php

namespace App\Model;

use DateTime;

final class Player extends Member {
    private int $id;
    private DateTime $birthdate;

    public function __construct(
        string $firstname,
        string $lastname,
        string $picture,
        DateTime $birthdate,
        int $id = 0
    ) {
        parent::__construct($firstname, $lastname, $picture);
        $this->id = $id;
        $this->birthdate = $birthdate;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getBirthdate(): DateTime {
        return $this->birthdate;
    }

    public function setBirthdate(DateTime $birthdate): void {
        $this->birthdate = $birthdate;
    }
}
