<?php

namespace App\Model\db;

use App\Model\Player;
use DateTime;
use PDOException;
use RuntimeException;
use Throwable;

class PlayerData
{
    /** @return Player[] */
    static public function getAllPlayers(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT member.id, member.firstname, member.lastname, member.picture, player.birthdate '
            . 'FROM player '
            . 'INNER JOIN member ON member.id = player.member_id '
            . 'ORDER BY member.lastname, member.firstname'
        );

        return array_map(
            static fn (array $row): Player => self::hydratePlayer($row),
            $statement->fetchAll()
        );
    }

    static public function getPlayer(int $id): ?Player
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT member.id, member.firstname, member.lastname, member.picture, player.birthdate '
                . 'FROM player '
                . 'INNER JOIN member ON member.id = player.member_id '
                . 'WHERE member.id = :id '
                . 'LIMIT 1'
            );

            $statement->execute(['id' => $id]);
            $row = $statement->fetch();

            return $row === false ? null : self::hydratePlayer($row);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Erreur lors de la récupération du joueur.",
                0,
                $e
            );
        }
    }

    static public function insertPlayer(Player $player): int
    {
        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare(
                'INSERT INTO member (firstname, lastname, picture) '
                . 'VALUES (:firstname, :lastname, :picture)'
            );
            $statement->execute([
                'firstname' => $player->getFirstname(),
                'lastname' => $player->getLastname(),
                'picture' => $player->getPicture(),
            ]);
            $playerId = (int) $pdo->lastInsertId();

            $statement = $pdo->prepare(
                'INSERT INTO player (member_id, birthdate) VALUES (:member_id, :birthdate)'
            );
            $statement->execute([
                'member_id' => $playerId,
                'birthdate' => $player->getBirthdate()->format('Y-m-d'),
            ]);

            $pdo->commit();
            $player->setId($playerId);
            return $playerId;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $exception;
        }
    }

    static public function updatePlayer(int $id, Player $player): void
    {
        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare(
                'SELECT member_id FROM player WHERE member_id = :id FOR UPDATE'
            );
            $statement->execute(['id' => $id]);
            if ($statement->fetchColumn() === false) {
                throw new RuntimeException('Le joueur à modifier est introuvable.');
            }

            $statement = $pdo->prepare(
                'UPDATE member SET firstname = :firstname, lastname = :lastname, picture = :picture '
                . 'WHERE id = :id'
            );
            $statement->execute([
                'firstname' => $player->getFirstname(),
                'lastname' => $player->getLastname(),
                'picture' => $player->getPicture(),
                'id' => $id,
            ]);

            $statement = $pdo->prepare(
                'UPDATE player SET birthdate = :birthdate WHERE member_id = :id'
            );
            $statement->execute([
                'birthdate' => $player->getBirthdate()->format('Y-m-d'),
                'id' => $id,
            ]);

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $exception;
        }
    }

    /** @param array<string, mixed> $row */
    private static function hydratePlayer(array $row): Player
    {
        return new Player(
            (string) $row['firstname'],
            (string) $row['lastname'],
            (string) $row['picture'],
            new DateTime((string) $row['birthdate']),
            (int) $row['id']
        );
    }
}