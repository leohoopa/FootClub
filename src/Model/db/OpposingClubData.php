<?php

namespace App\Model\db;

class OpposingClubData
{
    static public function getAllOpposingClubs(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT id, street_number, street, postal_code, city '
            . 'FROM opposing_club ORDER BY city'
        );

        return $statement->fetchAll();
    }

    static public function insertOpposingClub(
        string $streetNumber,
        string $street,
        string $postalCode,
        string $city
    ): int {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->prepare(
            'INSERT INTO opposing_club (street_number, street, postal_code, city) '
            . 'VALUES (:street_number, :street, :postal_code, :city)'
        );
        $statement->execute([
            'street_number' => $streetNumber,
            'street' => $street,
            'postal_code' => $postalCode,
            'city' => $city,
        ]);

        return (int) $pdo->lastInsertId();
    }
}