<?php
namespace App\FieldTypes\Validators;

class TelValidator implements ValidatorInterface {
    private string $pattern = '/^\+[0-9]{7,15}$/';

    public function validate($value): bool {
        return preg_match($this->pattern, $value) === 1;
    }

    public function getErrorMessage(): string {
        return "The telephone number must match the format: + followed by 7 to 15 digits.";
    }
}
