<?php

namespace App\Controller;

use App\Model\db\PlayerData;
use App\Model\Player;
use DateTime;
use RuntimeException;

class PlayerController
{
    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>, success: bool}
     */
    public static function process(array $input, ?int $id = null): array
    {
        $validated = self::validate($input);
        if ($validated['errors'] !== []) {
            return [
                'values' => $validated['values'],
                'errors' => $validated['errors'],
                'success' => false,
            ];
        }

        $values = $validated['values'];

        try {
            $player = new Player(
                $values['firstname'],
                $values['lastname'],
                $values['picture'],
                new DateTime($values['birthdate'])
            );

            if ($id === null) {
                PlayerData::insertPlayer($player);
            } else {
                PlayerData::updatePlayer($id, $player);
            }

            return ['values' => $values, 'errors' => [], 'success' => true];
        } catch (RuntimeException $exception) {
            error_log($exception->getMessage());
            return [
                'values' => $values,
                'errors' => [
                    '_form' => $id === null
                        ? "Une erreur est survenue pendant l'enregistrement du joueur."
                        : "Une erreur est survenue pendant la modification du joueur.",
                ],
                'success' => false,
            ];
        }
    }

    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>}
     */
    private static function validate(array $input): array
    {
        $errors = [];
        $values = [];

        foreach (['firstname', 'lastname', 'birthdate', 'picture'] as $field) {
            $value = $input[$field] ?? '';
            if (!is_string($value)) {
                $errors[$field] = 'Cette valeur est invalide.';
                $values[$field] = '';
                continue;
            }

            $values[$field] = trim($value);
            if (preg_match('//u', $values[$field]) !== 1) {
                $errors[$field] = 'Le texte contient des caractères invalides.';
                continue;
            }

            preg_match_all('/./us', $values[$field], $characters);
            $length = count($characters[0]);
            if ($length > 255) {
                $errors[$field] = 'Cette valeur ne doit pas dépasser 255 caractères.';
            }
        }

        foreach (['firstname' => 'prénom', 'lastname' => 'nom'] as $field => $label) {
            if ($values[$field] === '') {
                $errors[$field] = 'Le ' . $label . ' est obligatoire.';
            }
        }

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

        return ['errors' => $errors, 'values' => $values];
    }
}
