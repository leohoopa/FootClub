<?php

namespace App\Controller;

use App\Model\db\PlayerData;
use App\Model\db\TeamData;
use App\Model\Player;
use App\enum\Roles;
use DateTime;
use RuntimeException;

class PlayerController extends MemberController
{
    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>, success: bool}
     */
    public static function process(array $input, ?int $id = null): array
    {
        return self::processMember(
            $input,
            $id,
            static fn (array $input): array => self::validate($input),
            static function (array $values, ?int $id): void {
                $teamId = $values['team_id'] === '' ? null : (int) $values['team_id'];
                $role = $values['role'] === '' ? null : $values['role'];
                $player = new Player(
                    $values['firstname'],
                    $values['lastname'],
                    $values['picture'],
                    new DateTime($values['birthdate'])
                );

                if ($teamId !== null && TeamData::getTeam($teamId) === null) {
                    throw new RuntimeException("L'équipe sélectionnée est introuvable.");
                }

                if ($id === null) {
                    PlayerData::insertPlayer($player, $teamId, $role);
                } else {
                    PlayerData::updatePlayer($id, $player, $teamId, $role);
                }
            },
            'joueur'
        );
    }

    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>}
     */
    private static function validate(array $input): array
    {
        $validated = self::validateCommonFields(
            $input,
            ['firstname', 'lastname', 'picture', 'birthdate', 'team_id', 'role']
        );
        $errors = $validated['errors'];
        $values = $validated['values'];

        $birthdate = DateTime::createFromFormat('!Y-m-d', $values['birthdate']);
        $dateErrors = DateTime::getLastErrors();
        if (
            $birthdate === false
            || $birthdate->format('Y-m-d') !== $values['birthdate']
            || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
        ) {
            $errors['birthdate'] = 'Saisissez une date de naissance valide.';
        } elseif ($birthdate > new DateTime('today')) {
            $errors['birthdate'] = 'La date de naissance ne peut pas être dans le futur.';
        }

        if ($values['team_id'] !== '') {
            $teamId = filter_var(
                $values['team_id'],
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1, 'max_range' => 2147483647]]
            );
            if ($teamId === false) {
                $errors['team_id'] = 'Sélectionnez une équipe valide.';
            }

            $availableRoles = array_map(
                static fn (Roles $role): string => $role->value,
                Roles::cases()
            );
            if (!in_array($values['role'], $availableRoles, true)) {
                $errors['role'] = 'Sélectionnez un poste valide.';
            }
        } else {
            $values['role'] = '';
        }

        return ['errors' => $errors, 'values' => $values];
    }
}
