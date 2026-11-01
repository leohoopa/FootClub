<?php

namespace App\Controller;

use App\Model\db\MatchDataFoot;
use DateTime;
use RuntimeException;

class MatchController
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
            $date = DateTime::createFromFormat('!Y-m-d\TH:i', $values['match_date']);
            if ($date === false) {
                throw new RuntimeException('La date du match est invalide après validation.');
            }

            $scoresAndIds = [
                (int) $values['team_score'],
                (int) $values['opponent_score'],
                $date,
                $values['city'],
                (int) $values['team_id'],
                (int) $values['opposing_club_id'],
            ];
            if ($id === null) {
                MatchDataFoot::insertMatch(...$scoresAndIds);
            } else {
                MatchDataFoot::updateMatch($id, ...$scoresAndIds);
            }

            return ['values' => $values, 'errors' => [], 'success' => true];
        } catch (RuntimeException $exception) {
            error_log($exception->getMessage());

            return [
                'values' => $values,
                'errors' => [
                    '_form' => $id === null
                        ? "Une erreur est survenue pendant l'enregistrement du match."
                        : 'Une erreur est survenue pendant la modification du match.',
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
        foreach (['team_score', 'opponent_score', 'match_date', 'city', 'team_id', 'opposing_club_id'] as $field) {
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
            if (count($characters[0]) > 255) {
                $errors[$field] = 'Cette valeur ne doit pas dépasser 255 caractères.';
            }
        }

        foreach (['team_score' => 'score de notre équipe', 'opponent_score' => 'score adverse'] as $field => $label) {
            $integerValue = ltrim($values[$field], '0');
            $integerValue = $integerValue === '' ? '0' : $integerValue;
            if (
                $values[$field] === ''
                || filter_var(
                    $integerValue,
                    FILTER_VALIDATE_INT,
                    ['options' => ['min_range' => 0, 'max_range' => 2147483647]]
                ) === false
            ) {
                $errors[$field] = 'Saisissez un ' . $label . ' entier positif ou nul.';
            }
        }

        foreach (['team_id' => 'équipe', 'opposing_club_id' => 'club adverse'] as $field => $label) {
            $integerValue = ltrim($values[$field], '0');
            $integerValue = $integerValue === '' ? '0' : $integerValue;
            if (
                $values[$field] === ''
                || filter_var(
                    $integerValue,
                    FILTER_VALIDATE_INT,
                    ['options' => ['min_range' => 1, 'max_range' => 2147483647]]
                ) === false
            ) {
                $errors[$field] = 'Sélectionnez un ' . $label . ' valide.';
            }
        }

        if ($values['city'] === '') {
            $errors['city'] = 'La ville est obligatoire.';
        }

        $date = DateTime::createFromFormat('!Y-m-d\TH:i', $values['match_date']);
        $dateErrors = DateTime::getLastErrors();
        if (
            $date === false
            || $date->format('Y-m-d\TH:i') !== $values['match_date']
            || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
        ) {
            $errors['match_date'] = 'Saisissez une date et une heure valides.';
        }

        return ['errors' => $errors, 'values' => $values];
    }
}
