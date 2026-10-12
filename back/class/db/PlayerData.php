<?php

require_once __DIR__ . '/Database.php';

class PlayerData
{
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
}