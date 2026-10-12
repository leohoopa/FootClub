<?php

require_once __DIR__ . '/Database.php';

class MatchData
{
    function getAllMatches(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT id, team_score, opponent_score, match_date, city, team_id, opposing_club_id '
            . 'FROM match_foot ORDER BY match_date DESC'
        );

        return $statement->fetchAll();
    }

    function insertMatch(
        int $teamScore,
        int $opponentScore,
        DateTimeInterface $matchDate,
        string $city,
        int $teamId,
        int $opposingClubId
    ): int {
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
    }
}