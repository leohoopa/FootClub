<?php
class Team {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getNom() : string {
        return $this->nom;
    }

    public function setNom(string $nom) : void {
        $this->name = $nom;
    }
}
?>