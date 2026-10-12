<?php

require_once __DIR__ . '/classDatabase.php';

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

function getAllPlayers(): array
{
    $pdo = Database::getInstance()->getConnection();
    $statement = $pdo->query(
        'SELECT member.id, member.firstname, member.lastname, member.picture, player.birthdate '
        . 'FROM player '
        . 'INNER JOIN member ON member.id = player.member_id '
        . 'ORDER BY member.lastname, member.firstname'
    );

    return $statement->fetchAll();
}

function insertPlayer(
    string $firstname,
    string $lastname,
    string $picture,
    DateTimeInterface $birthdate
): int
{
    $pdo = Database::getInstance()->getConnection();
    $pdo->beginTransaction();

    try {
        $statement = $pdo->prepare(
            'INSERT INTO member (firstname, lastname, picture) '
            . 'VALUES (:firstname, :lastname, :picture)'
        );
        $statement->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'picture' => $picture,
        ]);
        $playerId = (int) $pdo->lastInsertId();

        $statement = $pdo->prepare(
            'INSERT INTO player (member_id, birthdate) VALUES (:member_id, :birthdate)'
        );
        $statement->execute([
            'member_id' => $playerId,
            'birthdate' => $birthdate->format('Y-m-d'),
        ]);

        $pdo->commit();
        return $playerId;
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

function getAllStaff(): array
{
    $pdo = Database::getInstance()->getConnection();
    $statement = $pdo->query(
        'SELECT member.id, member.firstname, member.lastname, member.picture, staff.role '
        . 'FROM staff '
        . 'INNER JOIN member ON member.id = staff.member_id '
        . 'ORDER BY member.lastname, member.firstname'
    );

    return $statement->fetchAll();
}

function insertStaff(string $firstname, string $lastname, string $picture, string $role): int
{
    $pdo = Database::getInstance()->getConnection();
    $pdo->beginTransaction();

    try {
        $statement = $pdo->prepare(
            'INSERT INTO member (firstname, lastname, picture) '
            . 'VALUES (:firstname, :lastname, :picture)'
        );
        $statement->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'picture' => $picture,
        ]);
        $staffId = (int) $pdo->lastInsertId();

        $statement = $pdo->prepare(
            'INSERT INTO staff (member_id, role) VALUES (:member_id, :role)'
        );
        $statement->execute([
            'member_id' => $staffId,
            'role' => $role,
        ]);

        $pdo->commit();
        return $staffId;
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

function getAllOpposingClubs(): array
{
    $pdo = Database::getInstance()->getConnection();
    $statement = $pdo->query(
        'SELECT id, street_number, street, postal_code, city '
        . 'FROM opposing_club ORDER BY city'
    );

    return $statement->fetchAll();
}

function insertOpposingClub(
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

function getAllPlayerHasTeams(): array
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