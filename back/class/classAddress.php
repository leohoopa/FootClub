<?php
class Address {

    private string $streetNumber;
    private string $street;
    private string $postalCode;
    private string $city;

    public function __construct(
        string $streetNumber,
        string $street,
        string $postalCode,
        string $city
    ) {
        $this->streetNumber = $streetNumber;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->city = $city;
    }

    public function getStreetNumber() : string {
        return $this->streetNumber;
    }

    public function getStreet() : string {
        return $this->street;
    }

    public function getPostalCode() : string {
        return $this->postalCode;
    }

    public function getCity() : string {
        return $this->city;
    }

    public function setStreetNumber(string $streetNumber) : void {
        $this->streetNumber = $streetNumber;
    }

    public function setStreet(string $street) : void {
        $this->street = $street;
    }

    public function setPostalCode(string $postalCode) : void {
        $this->postalCode = $postalCode;
    }

    public function setCity(string $city) : void {
        $this->city = $city;
    }
}
?>