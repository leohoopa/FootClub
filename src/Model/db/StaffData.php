<?php

require_once __DIR__ . '/Database.php';

class StaffData
{
    function static getAllStaff(): array
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

    function getStaff(int $id): ?array
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
            $staff = $statement->fetch();

            return $staff === false ? null : $staff;
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Erreur lors de la récupération du staff.",
                0,
                $e
            );
        }
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
}