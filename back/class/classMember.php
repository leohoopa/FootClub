<?php
abstract class Member {
    protected string $firstname;
    protected string $lastname;
    protected string $picture;

    public function __construct(string $firstname, string $lastname,string $picture) {
        $this->firstname = $firstname;
        $this->lastname = $$lastname;
        $this->picture = $picture;
    }

    public function getFirstname():string {
        return $his->firstname;
    }
    public function getLastname():string {
        return $this->lastname;
    }
    public function getPicture():string {
        return $this->picture;
    }

    public function setFirstname($firstname):void {
        $this->firstname = $firstname;
    }
    public function setLastname($lastname):void {
        $this->Lastname = $lastname;
    }
    public function setPicture($picture):void {
        $this->picture = $picture;
    } 
}
class player extends Membre {
    private datetime $birthdate;

    public function __construct(string $firstname, string $lastname,string $picture, datetime $birthdate) {
        parent:: __construct(string $firstname, string $lastname,string $picture);
        $this->birthdate = $birthdate;
    }

    public function getBirthdate():datetime {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate):void {
        $this->birthdate = $birthdate;
    }
}
class staff_Member extends Membre {
    private string $role;

    public function __construct(string $firstname, string $lastname,string $picture, string $role) {
        parent:: __construct(string $firstname, string $lastname,string $picture);
        $this->role = $role;
    }

    public function getRole():string {
        return $this->role;
    }
    public function setRole($role):void {
        $this->role = $role;
    }
}