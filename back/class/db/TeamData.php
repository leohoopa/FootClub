<?php

require_once __DIR__ . '/Database.php';

class TeamDataBase
{
    function getAllTeams(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query('SELECT id, name FROM team ORDER BY name');

        return $statement->fetchAll();
    }

    function insertTeam(string $name): int
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->prepare('INSERT INTO team (name) VALUES (:name)');
        $statement->execute(['name' => $name]);

        return (int) $pdo->lastInsertId();
    }
}