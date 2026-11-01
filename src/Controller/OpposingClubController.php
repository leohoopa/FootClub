<?php

namespace App\Controller;

use App\Model\db\OpposingClubData;
use RuntimeException;

class OpposingClubController
{
    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>, success: bool}
     */
    public static function process(array $input, ?int $id = null): array
    {
        $errors = [];
        $values = [];
        $fields = [
            'street_number' => ['label' => 'Le numéro de rue', 'max' => 50],
            'street' => ['label' => 'La rue', 'max' => 255],
            'postal_code' => ['label' => 'Le code postal', 'max' => 20],
            'city' => ['label' => 'La ville', 'max' => 255],
        ];

        foreach ($fields as $field => $metadata) {
            $value = $input[$field] ?? '';
            if (!is_string($value)) {
                $errors[$field] = 'Cette valeur est invalide.';
                $values[$field] = '';
                continue;
            }

            $values[$field] = trim($value);
            if (preg_match('//u', $values[$field]) !== 1) {
                $errors[$field] = 'Le texte contient des caractères invalides.';
            } elseif (preg_match_all('/./us', $values[$field], $characters) > 0 && count($characters[0]) > $metadata['max']) {
                $errors[$field] = 'Cette valeur ne doit pas dépasser ' . $metadata['max'] . ' caractères.';
            } elseif ($values[$field] === '') {
                $errors[$field] = $metadata['label'] . ' est obligatoire.';
            }
        }

        if ($errors !== []) {
            return ['values' => $values, 'errors' => $errors, 'success' => false];
        }

        try {
            if ($id === null) {
                OpposingClubData::insertOpposingClub(
                    $values['street_number'],
                    $values['street'],
                    $values['postal_code'],
                    $values['city']
                );
            } else {
                OpposingClubData::updateOpposingClub(
                    $id,
                    $values['street_number'],
                    $values['street'],
                    $values['postal_code'],
                    $values['city']
                );
            }

            return ['values' => $values, 'errors' => [], 'success' => true];
        } catch (RuntimeException $exception) {
            error_log($exception->getMessage());

            return [
                'values' => $values,
                'errors' => [
                    '_form' => $id === null
                        ? "Une erreur est survenue pendant l'enregistrement du club adverse."
                        : 'Une erreur est survenue pendant la modification du club adverse.',
                ],
                'success' => false,
            ];
        }
    }
}
