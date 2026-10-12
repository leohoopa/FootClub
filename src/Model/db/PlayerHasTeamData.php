<?php

require_once __DIR__ . '/Database.php';

class PlayerHasTeamData
{
    function static getAllPlayerHasTeams(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT id, player_id, team_id, role FROM player_has_team ORDER BY team_id, player_id'
        );

        return $statement->fetchAll();
    }

    function insertPlayerHasTeam(int $playerId, int $teamId, string $role): int
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->prepare(
            'INSERT INTO player_has_team (player_id, team_id, role) '
            . 'VALUES (:player_id, :team_id, :role)'
        );
        $statement->execute([
            'player_id' => $playerId,
            'team_id' => $teamId,
            'role' => $role,
        ]);

        return (int) $pdo->lastInsertId();
    }
}