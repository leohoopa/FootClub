<?php

namespace App\Model\db;

use DateTimeInterface;
use PDOException;
use RuntimeException;

class MatchDataFoot
{
    /** @return array<int, array<string, mixed>> */
    public static function getAllMatches(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT match_foot.id, match_foot.team_score, match_foot.opponent_score, '
            . 'match_foot.match_date, match_foot.city, match_foot.team_id, '
            . 'match_foot.opposing_club_id, team.name AS team_name, opposing_club.city AS opponent_city '
            . 'FROM match_foot '
            . 'INNER JOIN team ON team.id = match_foot.team_id '
            . 'INNER JOIN opposing_club ON opposing_club.id = match_foot.opposing_club_id '
            . 'ORDER BY match_foot.match_date DESC'
        );

        return $statement->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public static function getMatch(int $id): ?array
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT id, team_score, opponent_score, match_date, city, team_id, opposing_club_id '
                . 'FROM match_foot WHERE id = :id LIMIT 1'
            );
            $statement->execute(['id' => $id]);
            $match = $statement->fetch();

            return $match === false ? null : $match;
        } catch (PDOException $exception) {
            throw new RuntimeException('Erreur lors de la récupération du match.', 0, $exception);
        }
    }

    public static function insertMatch(
        int $teamScore,
        int $opponentScore,
        DateTimeInterface $matchDate,
        string $city,
        int $teamId,
        int $opposingClubId
    ): int {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'INSERT INTO match_foot '
                . '(team_score, opponent_score, match_date, city, team_id, opposing_club_id) '
                . 'VALUES (:team_score, :opponent_score, :match_date, :city, :team_id, :opposing_club_id)'
            );
            $statement->execute([
                'team_score' => $teamScore,
                'opponent_score' => $opponentScore,
                'match_date' => $matchDate->format('Y-m-d H:i:s'),
                'city' => $city,
                'team_id' => $teamId,
                'opposing_club_id' => $opposingClubId,
            ]);

            return (int) $pdo->lastInsertId();
        } catch (PDOException $exception) {
            throw new RuntimeException("Erreur lors de l'enregistrement du match.", 0, $exception);
        }
    }

    public static function updateMatch(
        int $id,
        int $teamScore,
        int $opponentScore,
        DateTimeInterface $matchDate,
        string $city,
        int $teamId,
        int $opposingClubId
    ): void {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'UPDATE match_foot SET team_score = :team_score, opponent_score = :opponent_score, '
                . 'match_date = :match_date, city = :city, team_id = :team_id, '
                . 'opposing_club_id = :opposing_club_id WHERE id = :id'
            );
            $statement->execute([
                'team_score' => $teamScore,
                'opponent_score' => $opponentScore,
                'match_date' => $matchDate->format('Y-m-d H:i:s'),
                'city' => $city,
                'team_id' => $teamId,
                'opposing_club_id' => $opposingClubId,
                'id' => $id,
            ]);

            if ($statement->rowCount() === 0 && self::getMatch($id) === null) {
                throw new RuntimeException('Le match à modifier est introuvable.');
            }
        } catch (PDOException $exception) {
            throw new RuntimeException('Erreur lors de la modification du match.', 0, $exception);
        }
    }
}