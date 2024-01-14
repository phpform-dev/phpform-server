<?php
namespace App\FieldTypes\Validators;

class DateValidator implements ValidatorInterface {
    private string $pattern = '/^\d{4}-\d{2}-\d{2}$/'; // "YYYY-MM-DD"

    public function validate($value): bool {
        if (preg_match($this->pattern, $value) !== 1) {
            return false;
        }

        $dateParts = explode('-', $value);
        return checkdate((int)$dateParts[1], (int)$dateParts[2], (int)$dateParts[0]);
    }

    public function getErrorMessage(): string {
        return "The date must be in the format 'YYYY-MM-DD'.";
    }
}
