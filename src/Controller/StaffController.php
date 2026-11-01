<?php

namespace App\Controller;

use App\Model\db\StaffData;
use App\Model\Staff;
use RuntimeException;

class StaffController
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
            $staff = new Staff(
                $values['firstname'],
                $values['lastname'],
                $values['picture'],
                $values['role']
            );

            if ($id === null) {
                StaffData::insertStaff($staff);
            } else {
                StaffData::updateStaff($id, $staff);
            }

            return ['values' => $values, 'errors' => [], 'success' => true];
        } catch (RuntimeException $exception) {
            error_log($exception->getMessage());
            return [
                'values' => $values,
                'errors' => [
                    '_form' => $id === null
                        ? "Une erreur est survenue pendant l'enregistrement du membre du staff."
                        : "Une erreur est survenue pendant la modification du membre du staff.",
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

        foreach (['firstname', 'lastname', 'role', 'picture'] as $field) {
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

        if ($values['role'] === '') {
            $errors['role'] = 'Le rôle est obligatoire.';
        }

        return ['errors' => $errors, 'values' => $values];
    }
}
