<?php
namespace App\FieldTypes\Validators;

class TimeValidator implements ValidatorInterface {
    private string $pattern = '/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/';

    public function validate($value): bool {
        return preg_match($this->pattern, $value) === 1;
    }

    public function getErrorMessage(): string {
        return "The time must be in the format 'hours:minutes', where hours is 00-23 and minutes is 00-59.";
    }
}
