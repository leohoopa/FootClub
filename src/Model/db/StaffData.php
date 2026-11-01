<?php

namespace App\Model\db;

use App\Model\Staff;
use PDOException;
use RuntimeException;
use Throwable;

class StaffData
{
    /** @return Staff[] */
    public static function getAllStaff(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $statement = $pdo->query(
            'SELECT member.id, member.firstname, member.lastname, member.picture, staff.role '
            . 'FROM staff '
            . 'INNER JOIN member ON member.id = staff.member_id '
            . 'ORDER BY member.lastname, member.firstname'
        );

        return array_map(
            static fn (array $row): Staff => self::hydrateStaff($row),
            $statement->fetchAll()
        );
    }

    public static function getStaff(int $id): ?Staff
    {
        try {
            $pdo = Database::getInstance()->getConnection();
            $statement = $pdo->prepare(
                'SELECT member.id, member.firstname, member.lastname, member.picture, staff.role '
                . 'FROM staff '
                . 'INNER JOIN member ON member.id = staff.member_id '
                . 'WHERE member.id = :id '
                . 'LIMIT 1'
            );

            $statement->execute(['id' => $id]);
            $row = $statement->fetch();

            return $row === false ? null : self::hydrateStaff($row);
        } catch (PDOException $e) {
            throw new RuntimeException(
                'Erreur lors de la récupération du staff.',
                0,
                $e
            );
        }
    }

    public static function insertStaff(Staff $staff): int
    {
        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare(
                'INSERT INTO member (firstname, lastname, picture) '
                . 'VALUES (:firstname, :lastname, :picture)'
            );
            $statement->execute([
                'firstname' => $staff->getFirstname(),
                'lastname' => $staff->getLastname(),
                'picture' => $staff->getPicture(),
            ]);
            $staffId = (int) $pdo->lastInsertId();

            $statement = $pdo->prepare(
                'INSERT INTO staff (member_id, role) VALUES (:member_id, :role)'
            );
            $statement->execute([
                'member_id' => $staffId,
                'role' => $staff->getRole(),
            ]);

            $pdo->commit();
            return $staffId;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($exception instanceof PDOException) {
                throw new RuntimeException(
                    "Erreur lors de l'enregistrement du membre du staff.",
                    0,
                    $exception
                );
            }
            throw $exception;
        }
    }

    public static function updateStaff(int $id, Staff $staff): void
    {
        $pdo = Database::getInstance()->getConnection();
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare(
                'SELECT member_id FROM staff WHERE member_id = :id FOR UPDATE'
            );
            $statement->execute(['id' => $id]);
            if ($statement->fetchColumn() === false) {
                throw new RuntimeException('Le membre du staff à modifier est introuvable.');
            }

            $statement = $pdo->prepare(
                'UPDATE member SET firstname = :firstname, lastname = :lastname, picture = :picture '
                . 'WHERE id = :id'
            );
            $statement->execute([
                'firstname' => $staff->getFirstname(),
                'lastname' => $staff->getLastname(),
                'picture' => $staff->getPicture(),
                'id' => $id,
            ]);

            $statement = $pdo->prepare(
                'UPDATE staff SET role = :role WHERE member_id = :id'
            );
            $statement->execute([
                'role' => $staff->getRole(),
                'id' => $id,
            ]);

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($exception instanceof PDOException) {
                throw new RuntimeException(
                    'Erreur lors de la modification du membre du staff.',
                    0,
                    $exception
                );
            }
            throw $exception;
        }
    }

    /** @param array<string, mixed> $row */
    private static function hydrateStaff(array $row): Staff
    {
        return new Staff(
            (string) $row['firstname'],
            (string) $row['lastname'],
            (string) $row['picture'],
            (string) $row['role'],
            (int) $row['id']
        );
    }
}