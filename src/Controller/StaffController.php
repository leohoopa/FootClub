<?php

namespace App\Controller;

use App\Model\db\StaffData;
use App\Model\Staff;

class StaffController extends MemberController
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
            },
            'membre du staff'
        );
    }

    /**
     * @param array<string, mixed> $input
     * @return array{errors: array<string, string>, values: array<string, string>}
     */
    private static function validate(array $input): array
    {
        $validated = self::validateCommonFields($input, ['firstname', 'lastname', 'picture', 'role']);
        $errors = $validated['errors'];
        $values = $validated['values'];

        if ($values['role'] === '') {
            $errors['role'] = 'Le rôle est obligatoire.';
        }

        return ['errors' => $errors, 'values' => $values];
    }
}
