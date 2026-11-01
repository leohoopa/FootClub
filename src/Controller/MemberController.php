<?php

namespace App\Controller;

use RuntimeException;

abstract class MemberController
{
    /**
     * @param array<string, mixed> $input
     * @param callable(array<string, mixed>): array{errors: array<string, string>, values: array<string, string>} $validate
     * @param callable(array<string, string>, ?int): void $persist
     * @return array{errors: array<string, string>, values: array<string, string>, success: bool}
     */
    protected static function processMember(
        array $input,
        ?int $id,
        callable $validate,
        callable $persist,
        string $subject
    ): array {
        $validated = $validate($input);
        if ($validated['errors'] !== []) {
            return [
                'values' => $validated['values'],
                'errors' => $validated['errors'],
                'success' => false,
            ];
        }

        $values = $validated['values'];

        try {
            $persist($values, $id);

            return ['values' => $values, 'errors' => [], 'success' => true];
        } catch (RuntimeException $exception) {
            error_log($exception->getMessage());

            return [
                'values' => $values,
                'errors' => [
                    '_form' => $id === null
                        ? "Une erreur est survenue pendant l'enregistrement du {$subject}."
                        : "Une erreur est survenue pendant la modification du {$subject}.",
                ],
                'success' => false,
            ];
        }
    }

    /**
     * @param array<string, mixed> $input
     * @param string[] $fields
     * @return array{errors: array<string, string>, values: array<string, string>}
     */
    protected static function validateCommonFields(array $input, array $fields): array
    {
        $errors = [];
        $values = [];

        foreach ($fields as $field) {
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

        foreach (['firstname' => 'prénom', 'lastname' => 'nom'] as $field => $label) {
            if ($values[$field] === '') {
                $errors[$field] = 'Le ' . $label . ' est obligatoire.';
            }
        }

        return ['errors' => $errors, 'values' => $values];
    }
}
