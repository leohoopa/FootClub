<?php

namespace App\Model\db;

use PDOException;
use RuntimeException;

class PlayerHasTeamData
{
    public static function getAllPlayerHasTeams(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT id, player_id, team_id, role FROM player_has_team ORDER BY team_id, player_id'
        );

        return $statement->fetchAll();
    }

    public static function insertPlayerHasTeam(int $playerId, int $teamId, string $role): int
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

    /** @return array<string, mixed>|null */
    public static function getPlayerTeamAssociation(int $playerId): ?array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT team_id, role FROM player_has_team WHERE player_id = :player_id ORDER BY id LIMIT 1'
            );
            $statement->execute(['player_id' => $playerId]);
            $association = $statement->fetch();

            return $association === false ? null : $association;
        } catch (PDOException $exception) {
            throw new RuntimeException('Erreur lors de la récupération de l’équipe du joueur.', 0, $exception);
        }
    }

    /** @return array<int, array<string, mixed>> */
    public static function getPlayersByTeam(int $teamId): array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT member.id, member.firstname, member.lastname, member.picture, player.birthdate, '
                . 'player_has_team.role '
                . 'FROM player_has_team '
                . 'INNER JOIN player ON player.member_id = player_has_team.player_id '
                . 'INNER JOIN member ON member.id = player.member_id '
                . 'WHERE player_has_team.team_id = :team_id '
                . 'ORDER BY member.lastname, member.firstname'
            );
            $statement->execute(['team_id' => $teamId]);

            return $statement->fetchAll();
        } catch (PDOException $exception) {
            throw new RuntimeException('Erreur lors de la récupération des joueurs de l’équipe.', 0, $exception);
        }
    }
}