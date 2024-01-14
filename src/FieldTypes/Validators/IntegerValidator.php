<?php
namespace App\FieldTypes\Validators;

readonly class IntegerValidator implements ValidatorInterface {

    public function validate($value): bool {
        return is_int($value);
    }

    public function getErrorMessage(): string {
        return "The value must be an integer.";
    }
}