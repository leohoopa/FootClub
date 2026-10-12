<?php

require_once __DIR__ . '/Database.php';

class TeamDataBase
{
    function static getAllTeams(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query('SELECT id, name FROM team ORDER BY name');

        return $statement->fetchAll();
    }

    function getTeam(int $id): ?array
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

    function insertTeam(string $name): int
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->prepare('INSERT INTO team (name) VALUES (:name)');
        $statement->execute(['name' => $name]);

        return (int) $pdo->lastInsertId();
    }
}