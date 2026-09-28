<?php

require_once __DIR__ . '/classAddress.php';

class OpposingClub {

    private Address $address;

    public function __construct(Address $address) {
        $this->address = $address;
    }

    public function getAddress() : Address {
        return $this->address;
    }

    public function setAddress(Address $address) : void {
        $this->address = $address;
    }
}
?>