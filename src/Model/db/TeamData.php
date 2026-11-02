<?php

namespace App\Model\db;

use PDOException;
use RuntimeException;

class TeamData
{
    public static function getAllTeams(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query('SELECT id, name FROM team ORDER BY name');

        return $statement->fetchAll();
    }

    public static function getTeam(int $id): ?array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT id, name FROM team WHERE id = :id LIMIT 1'
            );
            $statement->execute(['id' => $id]);
            $team = $statement->fetch();

            return $team === false ? null : $team;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Erreur lors de la récupération de l'équipe.",
                0,
                $e
            );
        }
    }

    public static function insertTeam(string $name): int
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare('INSERT INTO team (name) VALUES (:name)');
            $statement->execute(['name' => $name]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $exception) {
            throw new RuntimeException("Erreur lors de l'enregistrement de l'équipe.", 0, $exception);
        }
    }

    public static function updateTeam(int $id, string $name): void
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare('UPDATE team SET name = :name WHERE id = :id');
            $statement->execute(['name' => $name, 'id' => $id]);

            if ($statement->rowCount() === 0 && self::getTeam($id) === null) {
                throw new RuntimeException("L'équipe à modifier est introuvable.");
            }
        } catch (PDOException $exception) {
            throw new RuntimeException("Erreur lors de la modification de l'équipe.", 0, $exception);
        }
    }
}