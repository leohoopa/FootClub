<?php

namespace App\Model\db;

use PDOException;
use RuntimeException;

class OpposingClubData
{
    public static function getAllOpposingClubs(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT id, street_number, street, postal_code, city '
            . 'FROM opposing_club ORDER BY city'
        );

        return $statement->fetchAll();
    }

    public static function insertOpposingClub(
        string $streetNumber,
        string $street,
        string $postalCode,
        string $city
    ): int {
        try {
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
        } catch (PDOException $exception) {
            throw new RuntimeException("Erreur lors de l'enregistrement du club adverse.", 0, $exception);
        }
    }

    /** @return array<string, mixed>|null */
    public static function getOpposingClub(int $id): ?array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT id, street_number, street, postal_code, city '
                . 'FROM opposing_club WHERE id = :id LIMIT 1'
            );
            $statement->execute(['id' => $id]);
            $club = $statement->fetch();

            return $club === false ? null : $club;
        } catch (PDOException $exception) {
            throw new RuntimeException('Erreur lors de la récupération du club adverse.', 0, $exception);
        }
    }

    public static function updateOpposingClub(
        int $id,
        string $streetNumber,
        string $street,
        string $postalCode,
        string $city
    ): void {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'UPDATE opposing_club SET street_number = :street_number, street = :street, '
                . 'postal_code = :postal_code, city = :city WHERE id = :id'
            );
            $statement->execute([
                'street_number' => $streetNumber,
                'street' => $street,
                'postal_code' => $postalCode,
                'city' => $city,
                'id' => $id,
            ]);

            if ($statement->rowCount() === 0 && self::getOpposingClub($id) === null) {
                throw new RuntimeException('Le club adverse à modifier est introuvable.');
            }
        } catch (PDOException $exception) {
            throw new RuntimeException('Erreur lors de la modification du club adverse.', 0, $exception);
        }
    }
}