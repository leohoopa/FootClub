<?php

namespace App\Controller;

use App\Model\db\TeamData;
use RuntimeException;

class TeamController
{
    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>, success: bool}
     */
    public static function process(array $input, ?int $id = null): array
    {
        $name = $input['name'] ?? '';
        $errors = [];
        if (!is_string($name)) {
            $errors['name'] = 'Cette valeur est invalide.';
            $name = '';
        } else {
            $name = trim($name);
            if (preg_match('//u', $name) !== 1) {
                $errors['name'] = 'Le texte contient des caractères invalides.';
            } elseif (preg_match_all('/./us', $name, $characters) > 0 && count($characters[0]) > 255) {
                $errors['name'] = 'Le nom ne doit pas dépasser 255 caractères.';
            } elseif ($name === '') {
                $errors['name'] = "Le nom de l'équipe est obligatoire.";
            }
        }

        $values = ['name' => $name];
        if ($errors !== []) {
            return ['values' => $values, 'errors' => $errors, 'success' => false];
        }

        try {
            if ($id === null) {
                TeamData::insertTeam($name);
            } else {
                TeamData::updateTeam($id, $name);
            }

            return ['values' => $values, 'errors' => [], 'success' => true];
        } catch (RuntimeException $exception) {
            error_log($exception->getMessage());

            return [
                'values' => $values,
                'errors' => [
                    '_form' => $id === null
                        ? "Une erreur est survenue pendant l'enregistrement de l'équipe."
                        : "Une erreur est survenue pendant la modification de l'équipe.",
                ],
                'success' => false,
            ];
        }
    }
}
